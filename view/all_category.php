<?php
date_default_timezone_set('Asia/Dhaka');
$date_time = date('Y-m-d g:i:sA');
$userid = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : NULL;

$categoryData = $obj->view_all_ordered_by("tbl_category", "`tbl_category`.`cat_id` ASC");

if (isset($_GET['deleteCategoryId']) && !empty($_GET['deleteCategoryId'])) {

    $deleteId = $_GET['deleteCategoryId'];
    $obj->Delete_data('tbl_category', "cat_id = '$deleteId'");
    ?>
    <script>
        window.location = '?q=all_category';
    </script>
    <?php
}

if (isset($_POST['add_Category'])) {

    $form_tbl_Category = array(

        'cat_name' => $_POST['category_name'],
        'status' => 1,
    );

    $tbl_Category_add = $obj->insert_by_condition("tbl_category", $form_tbl_Category, "");
    ?>
    <script>
        window.location = '?q=all_category';
    </script>
    <?php

}
if (isset($_POST['editCategory'])) {

    $form_tbl_Category_edit = array(

        'cat_name' => $_POST['category_name'],

    );

    $tbl_Category_edit = $obj->Update_data("tbl_category", $form_tbl_Category_edit, "cat_id = ".$_POST['category_id']."");

    ?>
    <script>
        window.location = '?q=all_category';
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
<div class="col-md-12 bg-primary" style="margin-top:20px; margin-bottom: 15px;">
    <div class="col-md-6">
        <h4><strong>View All Category</strong></h4>
    </div>
    <div class="col-md-6" style="padding-top:5px;">
        <button type="button"
                data-toggle="modal" data-target="#addCategoryModel"
                class="btn btn-success btn-sm pull-right">
            <span class="glyphicon glyphicon-plus"></span> Add Category
        </button>
    </div>
</div>
<!-- all user show -->
<div id="div_all" class="row" style="font-size: 12px;">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" id="datatable">
                <thead class="bg-teal-700">
                <tr>
                    <th class="text-center col-md-1">SL</th>
                    <th class="text-center col-md-3">Category Name</th>
                    <th class="text-center col-md-3">Number of Product</th>
                    <th class="text-center col-md-4">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $i = 0;
                foreach ($categoryData as $category) {

                    $i++;
                    $numberOfProduct = $obj->Total_Count("tbl_item_with_price", "`category` = " . $category['cat_id'] . "");
                        ?>
                        <tr>
                            <td class="text-center">
                                <?php echo $i; ?>
                            </td>
                            <td class="text-center"><?php echo isset($category['cat_name']) ? $category['cat_name'] : NULL; ?></td>
                            <td class="text-center"><strong><?php echo $numberOfProduct; ?></strong></td>
                            <td class="text-center">
                                <div style="margin-top:5px">
                                    <button type="button"
                                            data-name="<?php echo isset ($category['cat_name']) ? $category['cat_name'] : null; ?>"
                                            data-category_id="<?php echo isset($category['cat_id']) ? $category['cat_id'] : NULL; ?>"
                                            data-toggle="modal" data-target="#editCategoryModel"
                                            class="btn btn-primary btn-xs">
                                        <span class="glyphicon glyphicon-edit"></span> Edit
                                    </button>
                                    <a type="button"
                                       onclick="return confirm('Are you sure you want to delete this Category Category?');"
                                       href="?q=all_category&deleteCategoryId=<?php echo $category['cat_id']; ?>"
                                       class="btn btn-danger btn-xs">
                                        <span class="glyphicon glyphicon-trash"></span> Delete
                                    </a>

                                </div>
                            </td>
                        </tr>
                        <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="editCategoryModel" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header text-center bg-slate">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>Edit Category </h4>
            </div>
            <form class="form-horizontal" role="form" enctype="multipart/form-data" method="post">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="category_name">Category Name </label>
                            <div class="col-sm-6">
                                <input type="text" name="category_name" value="" class="form-control">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="category_id" value="">
                </div>
                <div class="modal-footer text-center">
                    <div class="row">
                        <div class="text-center">
                            <button type="submit" class="btn btn-success" name="editCategory">Update</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="addCategoryModel" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center bg-teal">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>Add New Category </h4>
            </div>
            <form class="form-horizontal" role="form" enctype="multipart/form-data" method="post">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="category_name">Category Name </label>
                            <div class="col-sm-6">
                                <input type="text" required="required" name="category_name" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer text-center">
                    <div class="row">
                        <div class="text-center">
                            <button type="submit" class="btn btn-success" name="add_Category">Add Category</button>
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

        $('#datatable').on('click', '[data-target="#editCategoryModel"]', function () {

            var CategoryName = $(this).data('name');
            var CategoryId = $(this).data('category_id');

            $('div#editCategoryModel input[name="category_name"]').val(CategoryName);
            $('div#editCategoryModel input[name="category_id"]').val(CategoryId);

        });
    });
</script>