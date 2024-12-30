<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$categoryItemData = $obj->view_all_ordered_by("tbl_category", "`tbl_category`.`cat_id` ASC");

if (isset($_GET['deleteItemId']) && !empty($_GET['deleteItemId'])) {

    $deleteId = $_GET['deleteItemId'];
    $obj->Delete_data('tbl_item_with_price', "item_id = '$deleteId'");
    ?>
    <script>
        window.location = '?q=all_item';
    </script>
    <?php
}

if (isset($_POST['add_item'])) {

    $form_tbl_item = array(

        'item_name' => $_POST['item_name'],
        'item_price' => $_POST['price'],
        'category' => $_POST['category'],
        'status' => 1,

    );

    $tbl_item_add = $obj->insert_by_condition("tbl_item_with_price", $form_tbl_item, "");
    ?>
    <script>
        window.location = '?q=all_item';
    </script>
    <?php
}
if (isset($_POST['editItem'])) {

    $form_tbl_item_edit = array(

        'item_name' => $_POST['item_name'],
        'item_price' => $_POST['price'],

    );

    $tbl_item_edit = $obj->Update_data("tbl_item_with_price", $form_tbl_item_edit, "item_id = ".$_POST['item_id']."");

    ?>
    <script>
        window.location = '?q=all_item';
    </script>
    <?php
}

?>

<script>

    function numbersOnly(e) // Numeric Validation
    {
        var unicode = e.charCode ? e.charCode : e.keyCode
        if (unicode != 8) {
            if ((unicode < 2534 || unicode > 2543) && (unicode < 46 || unicode > 57)) {
                return false;
            } else if (unicode == 47) {
                return false;
            }
        }
    }
</script>
<?php echo isset($notification) ? $notification : NULL; ?>
<div class="col-md-12 bg-teal-600" style="margin-top:20px; margin-bottom: 15px;">
    <div class="col-md-6">
        <h4><strong>View All Item List</strong></h4>
    </div>
    <div class="col-md-6" style="padding-top:5px;">
        <button type="button"
                data-toggle="modal" data-target="#addItemModel"
                class="btn btn-success btn-sm pull-right">
            <span class="glyphicon glyphicon-plus"></span> Add Item
        </button>
    </div>
</div>
<!-- all user show -->
<div id="div_all" class="row" style="font-size: 12px;">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" id="datatable">
                <thead class="bg-slate-800">
                <tr>
                    <th class="text-center col-md-1">SL</th>
                    <th class="text-center col-md-3">Item Name</th>
                    <th class="text-center col-md-2">Item price</th>
                    <th class="text-center col-md-3">Category</th>
                    <th class="text-center col-md-4">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $i = 0;


                foreach ($categoryItemData as $category) {

                    $sellItemData = $obj->view_all_by_cond("tbl_item_with_price", "`category` = " . $category['cat_id'] . "");

                    foreach ($sellItemData as $item) {

                        $i++;
                        $item_id = $item['item_id'];

                        ?>
                        <tr>
                            <td class="text-center">
                                <strong><?php echo $i; ?></strong>
                            </td>
                            <td class="text-center"><?php echo isset($item['item_name']) ? $item['item_name'] : NULL; ?></td>
                            <td class="text-center"><?php echo isset($item['item_price']) ? number_format($item['item_price'], 1) . ' TK.' : NULL; ?></td>
                            <td class="text-center"><?php echo isset($item['category']) ? $category['cat_name'] : "No Category"; ?></td>
                            <td class="text-center">
                                <div style="margin-top:5px">
                                    <button type="button"
                                       data-name="<?php echo isset ($item['item_name']) ? $item['item_name'] : null; ?>"
                                       data-price="<?php echo isset($item['item_price']) ? $item['item_price'] : NULL; ?>"
                                       data-item_id="<?php echo isset($item['item_id']) ? $item['item_id'] : NULL; ?>"
                                       data-toggle="modal" data-target="#editItemModel"
                                       class="btn btn-success btn-xs">
                                        <span class="glyphicon glyphicon-edit"></span> Edit
                                    </button>
                                    <a type="button"
                                       onclick="return confirm('Are you sure you want to delete this Item item?');"
                                       href="?q=all_item&deleteItemId=<?php echo $item['item_id']; ?>"
                                       class="btn btn-danger btn-xs">
                                        <span class="glyphicon glyphicon-trash"></span> Delete
                                    </a>

                                </div>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="editItemModel" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header text-center bg-slate">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>Edit Item </h4>
            </div>
            <form class="form-horizontal" role="form" enctype="multipart/form-data" method="post">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="item_name">Item Name </label>
                            <div class="col-sm-6">
                                <input type="text" name="item_name" value="" class="form-control">
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom:20px;">
                            <label class="control-label col-sm-4" for="price">Item Price </label>
                            <div class="col-sm-6">
                                <input type="text" onkeypress="return numbersOnly(event)" name="price"
                                       value="" class="form-control">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="item_id" value="">
                </div>
                <div class="modal-footer text-center">
                    <div class="row">
                        <div class="text-center">
                            <button type="submit" class="btn btn-success" name="editItem">Update</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="addItemModel" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center bg-teal">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>Add Item </h4>
            </div>
            <form class="form-horizontal" role="form" enctype="multipart/form-data" method="post">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="item_name">Item Name </label>
                            <div class="col-sm-6">
                                <input type="text" name="item_name" required="required" class="form-control">
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom:20px;">
                            <label class="control-label col-sm-4" for="price">Item Price </label>
                            <div class="col-sm-6">
                                <input type="text" onkeypress="return numbersOnly(event)" required="required" name="price"
                                       class="form-control">
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom:20px;">
                            <label class="control-label col-sm-4" for="price">Category </label>
                            <div class="col-sm-6">
                                <select class="form-control" required="required" name="category">
                                    <option></option>
                                    <?php
                                    foreach ($obj->view_all("tbl_category") as $category) {
                                        ?>
                                        <option value="<?php echo isset($category['cat_id']) ? $category['cat_id'] : NULL; ?>"><?php echo isset($category['cat_name']) ? $category['cat_name'] : NULL; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer text-center">
                    <div class="row">
                        <div class="text-center">
                            <button type="submit" class="btn btn-success" name="add_item">Add Item</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {

        $('#datatable').on('click', '[data-target="#editItemModel"]', function () {

            var itemName = $(this).data('name');
            var itemPrice = $(this).data('price');
            var itemId = $(this).data('item_id');

            $('div#editItemModel input[name="item_name"]').val(itemName);
            $('div#editItemModel input[name="price"]').val(itemPrice);
            $('div#editItemModel input[name="item_id"]').val(itemId);

        });
    });
</script>