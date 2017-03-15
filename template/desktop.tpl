    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#"><img src="img/logo.png" class="logo" alt="logo"></a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
              {if (isset($session['id']) AND isset($session['pseudo']))}
                <a id="logout" href="index.php?logout=out"> <b>Logout</b> <i class="fa fa-sign-out" aria-hidden="true"></i></a>
              {else}
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><b>Login</b> <span class="caret"></span></a>
                <ul id="login-dp" class="dropdown-menu">
                  <li>
                    <div class="row">
                      <div class="col-md-12">
                        <form class="form" role="form" method="post" action="index.php" accept-charset="UTF-8" id="login-nav">
                          <div class="form-group">
                            <label class="sr-only" for="InputLogin">Login</label>
                            <input type="text" class="form-control" id="InputLogin" placeholder="Login" name="login" required autofocus>
                          </div>
                          <div class="form-group">
                            <label class="sr-only" for="InputPswd">Password</label>
                            <input type="password" class="form-control" id="InputPswd" placeholder="Password" name="pswd" required>
                          </div>
                          <input type="text" class="hidden" name="loggingin" value='login'/> <!-- pour passer l'ordre login -->
                          <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block">Sign in</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </li>
                </ul>
              {/if}
            </li>
          </ul>
        </div>
        <!-- /.navbar-collapse -->
      </div>
      <!-- /.container-fluid -->
    </nav>
    <div class="col-md-12 hidden-xs"> <!-- *********************************** debut panel **********************************-->
    <div class="panel panel-default panel-table">
      <div class="panel-heading">
        <h3 class="panel-title  text-center">{html_options name=namelg options=$myOptions selected=$bottle}</h3>
      </div>
      <div class="panel-body">
        <div class="table table-striped table-bordered table-list">
          <div class="thead">
            <div class="tr">
              <!--************** col Header ****************-->
              <div class="col-sm-1 th">Picture</div>
              <div class="col-sm-2 th">Name</div>
              <div class="col-sm-1 th">Year</div>
              <div class="col-sm-1 th">Grapes</div>
              <div class="col-sm-1 th">Country</div>
              <div class="col-sm-1 th">Region</div>
              <div class="col-sm-4 th">Description</div>
              <div class="col-sm-1 th btn-create-head">            
                {if (isset($session['id']) AND isset($session['pseudo']))} <!--************** button Create ****************-->
                  <button type="button" class="btn btn-sm btn-create" id="create">Create New</button>
                {else}
                    <button type="button" class="btn btn-sm btn-create disabled" id="create">Create New</button>
                {/if}
              </div>
            </div>
          </div>
          <div class="tbody">
          <div class="row" id="newRow">
            <!--**************  Create  ****************-->
            <div class="col-sm-12 tr">
              <form class="" method="post" action="index.php" enctype="multipart/form-data">
                <div class=" col-sm-1 td">
                  <div class="input-file-container">
                    <input type="file" name="picture" class="input-file input-file-create"/>
                    <label id="lbl-create" tabindex="0" class="input-file-trigger">Select Image</label>
                  </div>
                </div>

                <input type="text" class="hidden" name="create" value='create'/> <!-- pour passer l'ordre create -->
                
                <div class=" col-sm-2 td">
                  <input type="text" class="form-control newrow" name="name" placeholder="Name..." required autofocus/>
                </div>
                <div class=" col-sm-1 td">
                  <input type="number" class="form-control newrow year" name="year" placeholder="Year..." min="1" required/>
                </div>
                <div class=" col-sm-1 td">
                  <input type="text" class="form-control newrow" name="grapes" placeholder="Grapes..." required/>
                </div>
                <div class=" col-sm-1 td">
                  <input type="text" class="form-control newrow" name="country" placeholder="Country..." required/>
                </div>
                <div class=" col-sm-1 td">
                  <input type="text" class="form-control newrow" name="region" placeholder="Region..." required/>  
                </div>
                <div class=" col-sm-4 td">
                  <input type="text" class="form-control newrow" name="description" placeholder="Description..." required/>
                </div>
                <div class="col-sm-1 btn-center td">
                  <input type="submit" class="btn btn-md btn-info" value="OK">
                </div>
              </form>
            </div>
          </div>
          {foreach $elts as $elt}     <!--**********************      Affichage des pages *********************   -->
            <div class="row">
                <div class="col-sm-12 tr">
                  <div class="hidden"> <!--  récup l'ID necessaire pour l'update   -->
                      {$elt.id}
                  </div>
                  <div class="hidden">
                      {$elt.picture}      <!--      necessaire pour récup nom de l'image dans modal via js -->
                  </div>
                  <div class=" col-sm-1 td img-container" >     <!--      affiche l'image  -->
                      <img class="img-responsive effectfront" src="img/{$elt.picture}" alt="bouteille de {$elt.name}">
                  </div>
                  <div class="col-sm-2 td">
                      {$elt.name}
                  </div>
                  {foreach $elt as $value}
                    {if $value@index ge 2 && $value@index le 5}
                    <div class=" col-sm-1 td">
                        {$value}
                    </div>
                    {/if}
                  {/foreach}
                  <!-- fin for -->
                  <div class=" col-sm-4 td">
                      {$elt.description}      <!--      affiche la description   -->
                  </div>
                  <div class=" col-sm-1 td">
                      <button type="button" class="update {if !(isset($session['id']) AND isset($session['pseudo']))}disabled{/if}" data-toggle="modal" data-target="#myModal" >
                      <em class="fa fa-pencil"></em>
                      </button>
                      <form method="post" action="index.php" name="delete">
                        <input type="text" class="hidden" name="delete" value='delete'/> <!-- pour passer l'ordre delete -->
                        <button type="submit" class="delete {if !(isset($session['id']) AND isset($session['pseudo']))}disabled{/if}"><em class="fa fa-trash"></em></button>
                        <div class="hidden"><input type="text" name="Del_id" value="{$elt.id}"></div>
                      </form>
                  </div>
                </div>
            </div>
          {/foreach}
        </div>
        <!-- panel Body -->
      </div>
      </div>
      <div class="panel-footer">
        <div class="row">
          <div class="col-xs-4">Page {$page+1} of {$nb_pages}
          </div>
          <div class="col-xs-8">
            <ul class="pagination hidden-xs pull-right">
              {for $i=1 to $nb_pages}
                <li><a href="index.php?page={$i-1}" {if $i == ($page+1)} class="activeNumPage" {/if} >{$i}</a></li>
              {/for}
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!--******************************************** Modal Update pour ecran > xs ***************************************-->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="ModalLabel">Update plz</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form action="index.php" method="post" enctype="multipart/form-data">
              <div class="hidden">
                <label class="form-control-label">Id:</label>
                <input type="text" class="form-control" name="id">
              </div>
              <div class="form-group">
                <label class="form-control-label">Nom:</label>
                <input type="text" class="form-control" name="name">
              </div>
              <div class="form-group">
                <label class="form-control-label">Année:</label>
                <input type="number" class="form-control year" name="year" min="1">
              </div>
              <div class="form-group">
                <label class="form-control-label">Grapes:</label>
                <input type="text" class="form-control" name="grapes">
              </div>
              <div class="form-group">
                <label class="form-control-label">Country:</label>
                <input type="text" class="form-control" name="country">
              </div>
              <div class="form-group">
                <label class="form-control-label">Region:</label>
                <input type="text" class="form-control" name="region">
              </div>
              <div class="form-group">
                <label class="form-control-label">Description:</label>
                <input type="text" class="form-control" name="description">
              </div>
              <div class="form-group">
                <label class="form-control-label">Picture:</label>
                <label class="form-control-label picName"></label>
                <input type="text" class="form-control hidden" name="picture">
              </div>
              <input type="text" class="hidden" name="update" value='update'/> <!-- pour passer l'ordre update -->
              
              <div class="input-file-container">
                <input type="file" name="picture-file" class="input-file input-file-modal"/>
                <label id="lbl-modal" tabindex="0" class="input-file-trigger">Change Image</label>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">OK</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>