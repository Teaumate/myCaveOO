<!-- *******************************************   page pour mobiles   ******************************************************** -->
<div class="container visible-xs-block container-xs">
  <div class="row">     <!-- entête bouttons *********** -->
    <div class="col-xs-1">
      <a href="index.php?bottle={$bottle}&direction=left" class="hidden"><i class="fa fa-arrow-circle-left fa-2x" aria-hidden="true"></i></a>
    </div>
    <div class="col-xs-10 text-center">
      <button type="button" class="update create-xs {if !(isset($session['id']) AND isset($session['pseudo']))}disabled{/if}" data-toggle="modal" data-target="#CreateModal" >
        <em class="fa fa-plus"></em>
      </button>
      <button type="button" class="update update-xs {if !(isset($session['id']) AND isset($session['pseudo']))}disabled{/if}" data-toggle="modal" data-target="#UpdateModal" >
        <i class="fa fa-pencil"></i>
      </button>
      <form method="post" action="index.php" class="btn-delete-xs">
        <input type="text" class="hidden" name="delete" value='delete'/> <!-- pour passer l'ordre delete -->
        <button type="submit" class="delete delete-xs {if !(isset($session['id']) AND isset($session['pseudo']))}disabled{/if}" >
          <i class="fa fa-trash"></i>
        </button>
        <div class="hidden"><input type="text" name="Del_id" value="{$elts[0].id}"></div> <!-- masqué on récup l'ID -->
      </form>
    </div>
    <div class="col-xs-1 text-right">
    <a href="index.php?bottle={$bottle}&direction=right" class="hidden"><i class="fa fa-arrow-circle-right fa-2x" aria-hidden="true"></i></a>
    </div>
  </div >       <!-- fin entête bouttons *************** -->
  <form class="form-horizontal">
    <fieldset class="fieldset-xs">
    <div class="form-group">
      <label class="col-xs-3 control-label text-right">Name</label>  
      <div class="col-xs-9">
        {html_options name=name options=$myOptions selected=$bottle}
      </div>
    </div>
    <div class="form-group">
      <label class="col-xs-3 control-label text-right">Year</label>  
      <div class="col-xs-9">
        <input disabled name="Year" type="number" value="{$elts[0].year}" class="form-control input-xs year">
      </div>
    </div>
    <div class="form-group">
      <label class="col-xs-3 control-label text-right">Grapes</label>  
      <div class="col-xs-9">
        <input disabled name="Grapes" type="text" value="{$elts[0].grapes}" class="form-control input-xs grapes">
      </div>
    </div>
    <div class="form-group">
      <label class="col-xs-3 control-label text-right">Country</label>  
      <div class="col-xs-9">
        <input disabled name="Country" type="text" value="{$elts[0].country}" class="form-control input-xs country">
      </div>
    </div>
    <div class="form-group">
      <label class="col-xs-3 control-label text-right">Region</label>  
      <div class="col-xs-9">
        <input disabled name="Region" type="text" value="{$elts[0].region}" class="form-control input-xs region">
      </div>
    </div>
    <div class="form-group">
      <label class="col-xs-3 control-label text-right">Description</label>  
      <div class="col-xs-9">
        <p class="description-xs disabled description">{$elts[0].description}</p>
      </div>
    </div>
    <div class="form-group">
      <div class="col-xs-12">
          <img class="img-responsive img-responsive-xs" src="img/{$elts[0].picture}" alt="bouteille de {$elts[0].name}">
      </div>
    </div>
    </fieldset>
  </form>
</div>
    <!--******************************************** page Modal Create *****************************************-->
    <div class="modal fade" id="CreateModal" tabindex="-1" role="dialog" aria-labelledby="ModalLbl" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="ModalLbl">Nvle Teteille</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form action="index.php" method="post" enctype="multipart/form-data">
              <div class="form-group">
                <label class="form-control-label">Nom:</label>
                <input type="text" class="form-control" name="name" required>
              </div>
              <div class="form-group">
                <label class="form-control-label">Année:</label>
                <input type="number" class="form-control year" name="year" min="0" required>
              </div>
              <div class="form-group">
                <label class="form-control-label">Grapes:</label>
                <input type="text" class="form-control" name="grapes" required>
              </div>
              <div class="form-group">
                <label class="form-control-label">Country:</label>
                <input type="text" class="form-control" name="country" required>
              </div>
              <div class="form-group">
                <label class="form-control-label">Region:</label>
                <input type="text" class="form-control" name="region" required>
              </div>
              <div class="form-group">
                <label class="form-control-label">Description:</label>
                <input type="text" class="form-control" name="description" required>
              </div>
              <input type="text" class="hidden" name="create" value='create'/> <!-- pour passer l'ordre create -->

                  <div class="input-file-container">
                    <input type="file" name="picture" class="input-file input-file-create-xs"/>
                    <label id="lbl-create-xs" tabindex="0" class="input-file-trigger">Select Image</label>
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
     <!--******************************************** page Modal Update *****************************************-->
     <div class="modal fade" id="UpdateModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabl" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="ModalLabl">Nvle Teteille</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form action="index.php" method="post" enctype="multipart/form-data">
              <div class="hidden">
                <label class="form-control-label">Id:</label>
                <input type="text" class="form-control" name="id" value="{$elts[0].id}">
              </div>
              <div class="form-group">
                <label class="form-control-label">Nom:</label>
                <input type="text" class="form-control" name="name" value="{$elts[0].name}">
              </div>
              <div class="form-group">
                <label class="form-control-label">Année:</label>
                <input type="number" class="form-control year" name="year" value="{$elts[0].year}" min="0">
              </div>
              <div class="form-group">
                <label class="form-control-label">Grapes:</label>
                <input type="text" class="form-control" name="grapes" value="{$elts[0].grapes}">
              </div>
              <div class="form-group">
                <label class="form-control-label">Country:</label>
                <input type="text" class="form-control" name="country" value="{$elts[0].country}">
              </div>
              <div class="form-group">
                <label class="form-control-label">Region:</label>
                <input type="text" class="form-control" name="region" value="{$elts[0].region}">
              </div>
              <div class="form-group">
                <label class="form-control-label">Description:</label>
                <input type="text" class="form-control" name="description" value="{$elts[0].description}">
              </div>
              <div class="form-group">
                <label class="form-control-label">Picture:</label>
                <label class="form-control-label"></label>
                <input type="text" class="form-control hidden" name="picture" value="{$elts[0].picture}">
              </div>
              <input type="text" class="hidden" name="update" value='update'/> <!-- pour passer l'ordre update -->

                  <div class="input-file-container">
                    <input type="file" name="picture-file" class="input-file input-file-update-xs">
                    <label id="lbl-update-xs" tabindex="0" class="input-file-trigger">Change Image</label>
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