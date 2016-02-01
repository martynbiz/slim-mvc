@extends('layouts.frontend')

@section('content')
    <div class="homebox">
        <div class="col-md-4 image">
          <div id="carousel" class="carousel slide" data-ride="carousel">
            <!-- Indicators -->
            <ol class="carousel-indicators">
              <li data-target="#carousel" data-slide-to="0" class="active"></li>
              <li data-target="#carousel" data-slide-to="1"></li>
              <li data-target="#carousel" data-slide-to="2"></li>
              <li data-target="#carousel" data-slide-to="3"></li>
            </ol>

            <!-- Wrapper for slides -->
            <div class="carousel-inner" role="listbox">
              <div class="item active">
                <a href="#"><img src="/photos/shunga.jpg" alt="Chania" width="460" height="345"></a>
              </div>

              <div class="item">
                <img src="/photos/earthparade.jpg" alt="Chania" width="460" height="345">
              </div>

              <div class="item">
                <img src="/photos/vietnamkids.jpg" alt="Flower" width="460" height="345">
              </div>

              <div class="item">
                <img src="/photos/waoffice.jpg" alt="Flower" width="460" height="345">
              </div>
            </div>

            <!-- Left and right controls -->
            <a class="left carousel-control" href="#carousel" role="button" data-slide="prev">
              <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
              <span class="sr-only">Previous</span>
            </a>
            <a class="right carousel-control" href="#carousel" role="button" data-slide="next">
              <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
              <span class="sr-only">Next</span>
            </a>
          </div>
        </div>

        <div class="col-md-8 body">
            <h1>Welcome to my website</h1>
            <p>This is my blog slash about slash portfolio slash homepage. He'll you'll find stuff I've written about web technologies I use as well as other interests. I'll be adding new content from time to time so do check back.</p>
            <hr>
            <ul class="tags">
                <li><a href="#">About me</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="homebox">
                <div class="col-md-3 image">
                    <img src="/photos/shunga.jpg" class="img-responsive">
                </div>
                <div class="col-md-9 body">
                    <h2>Shungs</h2>
                    <p>Slim framework blah blah blah</p>
                    <p class="small">Written by {{ 'article.author.name' }} | {{ 'article.created_at' }}</p>
                </div>
            </div>

            <div class="homebox">
                <div class="col-md-12 body">
                    <h2>Earth parade</h2>
                    <p>Slim framework blah blah blah</p>
                    <p class="small">Written by {{ 'article.author.name' }} | {{ 'article.created_at' }}</p>
                </div>
            </div>

            <div class="homebox">
                <div class="col-md-3 image">
                    <img src="/photos/vietnamkids.jpg" class="img-responsive">
                </div>
                <div class="col-md-9 body">
                    <h2>Cool kids in Vietnam</h2>
                    <p>Slim framework blah blah blah</p>
                    <p class="small">Written by {{ 'article.author.name' }} | {{ 'article.created_at' }}</p>
                </div>
            </div>

            <div class="homebox">
                <div class="col-md-3 image">
                    <img src="/photos/waoffice.jpg" class="img-responsive">
                </div>
                <div class="col-md-9 body">
                    <h2>Waaaa office!</h2>
                    <p>Slim framework blah blah blah</p>
                    <p class="small">Written by {{ 'article.author.name' }} | {{ 'article.created_at' }}</p>
                </div>
            </div>

            <div class="homebox">
                <div class="col-md-3 image">
                    <img src="/photos/vietnamkids.jpg" class="img-responsive">
                </div>
                <div class="col-md-9 body">
                    <h2>Cool kids in Vietnam</h2>
                    <p>Slim framework blah blah blah</p>
                    <p class="small">Written by {{ 'article.author.name' }} | {{ 'article.created_at' }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="homebox">
                <div class="col-md-12 body">
            		<form method="get" action="/">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search for...">
                            <span class="input-group-btn">
                                <button class="btn btn-primary" type="button"><i class="glyphicon glyphicon-search"></i>&nbsp;</button>
                            </span>
                        </div>
            		</form>
                </div>
            </div>

            <div class="homebox">
                <div class="col-md-12 body">
                    <h3>Tags</h3>

                    {{-- {% if tags %}
                    <ul class="tags">
                        {% for tag in tags %}
                        <li><a href="/{{ tag.slug }}">{{ tag.name }}</a></li>
                        {% endfor %}
                    </ul>
                    {% endif %} --}}
                </div>
            </div>

            <div class="homebox">
                <div class="col-md-12 body">
                    <h3>Featured</h3>

                    <ol class="featured">
                        <li><a href="#">A Bootstrap/LESS work-flow</a></li>
                        <li><a href="#">Deploying with Git</a></li>
                        <li><a href="#">Karaoke on Rails!</a></li>
                    </ol>
                </div>
            </div>

            <div class="banner">
                <a href="https://www.opendemocracy.net/" target="_blank">
                    <img src="/images/banner_od.jpg" class="img-responsive">
                </a>
            </div>

            <div class="banner">
                <a href="http://www.neweconomics.org/" target="_blank">
                    <img src="/images/banner_nef.jpg" class="img-responsive">
                </a>
            </div>
        </div>
    </div>
@stop
