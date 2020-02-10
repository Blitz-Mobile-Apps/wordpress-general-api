<?php
do_action( 'booking_script_css'); 

$taxonomyName = "product_cat";
$parent_terms = get_terms($taxonomyName, array('orderby' => 'slug', 'hide_empty' => false) );

if (!empty($_POST)) {
  $data = [];
  if (!empty($_POST['appdata']['homebanner']['image'])) {
    $i = 0;
    foreach ($_POST['appdata']['homebanner']['image'] as $key => $value) {
      $data['banners'][$i]['image'] = $_POST['appdata']['homebanner']['image'][$key];
      $data['banners'][$i]['category'] = $_POST['appdata']['homebanner']['category'][$key];
      $data['banners'][$i]['button'] = $_POST['appdata']['homebanner']['button'][$key];
      $i++;
    }
  }
  if (!empty($_POST['appdata']['pages']['page_name'])) {
    $i = 0;
    foreach ($_POST['appdata']['pages']['page_name'] as $key1 => $value1) {
      $data['pages'][$value1]['image'] = $_POST['appdata']['pages']['image'][$key1];
      $data['pages'][$value1]['title'] = $_POST['appdata']['pages']['title'][$key1];
      $data['pages'][$value1]['content'] = $_POST['appdata']['pages']['content'][$key1];
      $i++;
    }
  }
  $data = json_encode($data);
	update_option('GAPI_app_data', $data);
}

$getdata = get_option('GAPI_app_data');
$getdata = json_decode($getdata , true); 
?>


<div class="card">
  <h3 class="card-header">Generate APP Data API
    <!-- <code style="float: right;">http://localhost/Plugin_api/wp-admin/admin.php?page=GPA_main_menu</code> -->
  </h3>
  <div class="card-body">



    <h3>Banner</h3>
    <div class="row">
      <div class="col-md-5">
        <label for="staticEmail" class="col-form-label">Image:</label>
        <input type="text" name="appdata[homebanner][image][]" class="form-control imaagebanner" required="required">
      </div>
      <div class="col-md-3">
        <label for="staticEmail" class="col-form-label">Category:</label>
        <select name="appdata[homebanner][category][]" class="form-control categorybanner" required="required">
          <option>Select</option>
          <?php if ($parent_terms): ?>
            <?php foreach ($parent_terms as $key): ?>
              <option value="<?php echo $key->term_id; ?>"><?php echo $key->name; ?></option>
            <?php endforeach ?>
          <?php endif ?>
        </select>
      </div>
      <div class="col-md-3">
        <label for="staticEmail" class="col-form-label">Button:</label>
        <input type="text" name="appdata[homebanner][button][]" class="form-control btnbanner" required="required">
      </div>
      <div class="col-md-1">
        <label for="staticEmail" class="col-md-11 col-form-label" style="color: white">Button:</label>
        <button type="button" class="btn btn-default" id="addbanner">Add</button>
      </div>
    </div>

  <br><br>
    <h3>Add Pages</h3>
    <div class="row">
      <div class="col-md-6">
        <label for="staticEmail" class="col-form-label">Page Name e.g (privacy_policy)</label>
        <input type="text" class="form-control pagename" required="required">
      </div>
      <div class="col-md-6">
        <label for="staticEmail" class="col-form-label">Title:</label>
        <input type="text" class="form-control pagetitle" required="required">
      </div>
      <div class="col-md-12">
        <label for="staticEmail" class="col-form-label">Content:</label>
        <textarea class="form-control pagecontent" rows="3" required="required"></textarea>
      </div>
      <div class="col-md-12">
        <label for="staticEmail" class="col-form-label">Image:</label>
        <input type="text" class="form-control pageimage" required="required">
      </div>
      <div class="col-md-12">
        <br>
       <button type="button" class="btn btn-default" id="addPages">Add</button>
      </div>
    </div> 


</div>
</div>




<div class="card">
  <h3 class="card-header">Generate Data</h3>
  <div class="card-body">
    <br>
    <form accept="" method="POST">

      <div class="dataapend">
        


        <?php if (!empty($getdata['banners'])): ?>
          <?php foreach ($getdata['banners'] as $key1): ?>
            <div class="row">
              <div class="col-md-5">
                <label for="staticEmail" class="col-form-label">Image:</label>
                <input type="text" name="appdata[homebanner][image][]" class="form-control" required="required" value="<?php echo $key1['image']; ?>">
              </div>
              <div class="col-md-3">
                <label for="staticEmail" class="col-form-label">Category:</label>
                <input type="text" name="appdata[homebanner][category][]" class="form-control" required="required" value="<?php echo $key1['category']; ?>">
              </div>
              <div class="col-md-3">
                <label for="staticEmail" class="col-form-label">Button:</label>
                <input type="text" name="appdata[homebanner][button][]" class="form-control" required="required" value="<?php echo $key1['button']; ?>">
              </div>
              <div class="col-md-1">
                <label for="staticEmail" class="col-md-11 col-form-label" style="color: white">Button:</label>
                <button type="button" class="btn btn-default delete">Remove</button>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>

  
  



      </div>
      <br>
      <br>
      <br>
      <br>
      <div class="dataapendPage">
        

          <?php if (!empty($getdata['pages'])): ?>
          <?php foreach ($getdata['pages'] as $key2 => $value2): ?>
              <div class="row">
                <div class="col-md-2">
                  <label for="staticEmail" class="col-form-label">Page Name e.g (privacy_policy)</label>
                  <input type="text" name="appdata[pages][page_name][]" class="form-control" required="required" value="<?php echo $key2; ?>">
                </div>
                <div class="col-md-3">
                  <label for="staticEmail" class="col-form-label">Title:</label>
                  <input type="text" name="appdata[pages][title][]" class="form-control" required="required" value="<?php echo $value2['title']; ?>">
                </div>
                <div class="col-md-3">
                  <label for="staticEmail" class="col-form-label">Content:</label>
                  <textarea name="appdata[pages][content][]" class="form-control" rows="3" required="required"><?php echo $value2['content']; ?></textarea>
                </div>
                <div class="col-md-3">
                  <label for="staticEmail" class="col-form-label">Image:</label>
                  <input type="text" name="appdata[pages][image][]" class="form-control" required="required" value="<?php echo $value2['image']; ?>">
                </div>
                <div class="col-md-1">
                  <label for="staticEmail" class="col-md-11 col-form-label" style="color: white">123:</label>
                  <button type="button" class="btn btn-default delete">Remove</button>
                </div>
              </div>
          <?php endforeach; ?>
        <?php endif; ?>



      </div>
      <div class="form-group row">
        <div class="col-sm-10">
          <br>
          <button type="submit" class="btn btn-primary">Update</button>
        </div>
      </div>
    </form>
  </div>
</div>



<script>
  jQuery(document).on('click', '#addbanner', function(event) {
    event.preventDefault();
    var img = jQuery(this).closest('.row').find('input.imaagebanner').val();
    var cat = jQuery(this).closest('.row').find('select.categorybanner').val();
    var btn = jQuery(this).closest('.row').find('input.btnbanner').val();
    jQuery('.dataapend').append(`
      <div class="row">
      <div class="col-md-5">
      <label for="staticEmail" class="col-form-label">Image:</label>
      <input type="text" name="appdata[homebanner][image][]" class="form-control" required="required" value="`+img+`">
      </div>
      <div class="col-md-3">
      <label for="staticEmail" class="col-form-label">Category:</label>
      <input type="text" name="appdata[homebanner][category][]" class="form-control" required="required" value="`+cat+`">
      </div>
      <div class="col-md-3">
      <label for="staticEmail" class="col-form-label">Button:</label>
      <input type="text" name="appdata[homebanner][button][]" class="form-control" required="required" value="`+btn+`">
      </div>
      <div class="col-md-1">
      <label for="staticEmail" class="col-md-11 col-form-label" style="color: white">Button:</label>
      <button type="button" class="btn btn-default delete">Remove</button>
      </div>
      </div>
      `);
  });

  jQuery(document).on('click', '#addPages', function(event) {
    event.preventDefault();

    var pagename = jQuery(this).closest('.row').find('.pagename').val();
    var pagetitle = jQuery(this).closest('.row').find('.pagetitle').val();
    var pagecontent = jQuery(this).closest('.row').find('.pagecontent').val();
    var pageimage = jQuery(this).closest('.row').find('.pageimage').val();
    
    jQuery('.dataapendPage').append(`
        <div class="row">
        <div class="col-md-2">
        <label for="staticEmail" class="col-form-label">Page Name e.g (privacy_policy)</label>
        <input type="text" name="appdata[pages][page_name][]" class="form-control" required="required" value="`+pagename+`">
        </div>
        <div class="col-md-3">
        <label for="staticEmail" class="col-form-label">Title:</label>
        <input type="text" name="appdata[pages][title][]" class="form-control" required="required" value="`+pagetitle+`">
        </div>
        <div class="col-md-3">
        <label for="staticEmail" class="col-form-label">Content:</label>
        <textarea name="appdata[pages][content][]" class="form-control" rows="3" required="required">`+pagecontent+`</textarea>
        </div>
        <div class="col-md-3">
        <label for="staticEmail" class="col-form-label">Image:</label>
        <input type="text" name="appdata[pages][image][]" class="form-control" required="required" value="`+pageimage+`">
        </div>
        <div class="col-md-1">
        <label for="staticEmail" class="col-md-11 col-form-label" style="color: white">123:</label>
        <button type="button" class="btn btn-default delete">Remove</button>
        </div>
        </div>
      `);
  });
  jQuery(document).on('click', '.delete', function(event) {
    event.preventDefault();
    jQuery(this).closest('.row').remove();
  });
</script>