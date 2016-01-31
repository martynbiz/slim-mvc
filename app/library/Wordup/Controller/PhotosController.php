<?php
namespace Wordup\Controller;

class PhotosController extends BaseController
{
    /**
     * Will fetch a cached photo from the orginal photos
     * Photo will be fetched by the params in the in URL (routed to here)
     * @param string
     */
    public function cached($path)
    {
        $container = $this->app->getContainer();
        $settings = $container->get('settings');

        // check if cached file exists for this photo
        if (file_exists($path)) {
            // cached image exists, return it

        } else {
            // cached image doesn't exist, create one

            // get the params from the $path (mainly just $id and $dim)
            list($ym, $d, $id, $dim) = explode('/', $path);

            // get photo by id
            $photo = $this->get('model.photo')->findOne(array(
                'id' => (int) $id,
            ));

            // this will generate a path to the cached file eg. 201601/31/100x100.jpg
            $srcPath = $settings['photos_dir']['original'] . $photo->getOriginalPath($dim);
            $destPath = $settings['photos_dir']['cache'] . $photo->getCachedPath($dim);

            // get the dimensions so we can calculate the width/height ratio
            // throw an exception if this fails
            list($width_orig, $height_orig) = getimagesize($srcPath);
            if (!$width_orig or !$height_orig)
                throw new \Exception('Could not get image size from uploaded image.');



            // calculate new image size with ratio if exceeds max
            // TODO put this into Photo as static, unit test
            $ratio_orig = $width_orig/$height_orig;

            // // Set a maximum height and width
            // $width = 2000;
            // $height = 2000;
            // if ($width/$height > $ratio_orig) {
            //    $width = ceil($height*$ratio_orig);
            // } else {
            //    $height = ceil($width/$ratio_orig);
            // }

            // check dimensions are valid
            // valid dimansions are:
            //   - 100x100 (set both height and width)
            //   - 100x (set width, calculate height)
            //   - x100 (set height, calculate width)
            list($width, $height) = explode('x', $dim);
            if (empty($width) and empty($height)) {
                throw new \Exception('Photo dimensions not defined properly.');
            } elseif (empty($width)) {
                $width = ceil($height/$ratio_orig);
            } elseif (empty($height)) {
                $height = ceil($width/$ratio_orig);
            }

            // Create a new image from the uploaded file
            $src = imagecreatefromjpeg($srcPath);
            if (!$src)
                throw new \Exception('Only JPEG images are allowed for photos.');

            // Create a new true color image and copy and resize part of an image
            // with resampling
            $tmp = imagecreatetruecolor($width, $height);
            imagecopyresampled($tmp, $src, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);

            // generate the photo dir from the target id
            // we'll use Photo::getCurrentDir to generate the dir from date
            // useful when managing thousands of photos/articles
            // e.g. /var/www/.../data/photos/201601/31/
            $dir = $settings['photos_dir']['cache'] . '/' . $photo->getDir();
            if (!file_exists($dir) and !mkdir($dir, 0775, true)) {
                throw new \Exception('Could not create directory');
            }

            imagejpeg($tmp, $destPath);
            imagedestroy($tmp);
        }
    }
}
