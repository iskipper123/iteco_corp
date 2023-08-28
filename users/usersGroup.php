<?  
 require_once '../dashbord/dashbord.php';?>
 <h1>Список Отделов</h1>
    
  <?php  $title = 'Список Отделов';
	
	$db = DB::getObject();
	$result_set = $db->getAllUsersDepartments();

$addConfirmButton= false;

    require_once '../partsOfPages/head.php';
    require_once "../partsOfPages/menuForAdmin.php"; ?>
    <? if(isset($_GET["success"])) {?>
        <div class="col-md-4">
            <div class="alert alert-success" role="alert">
                Данные успешно сохранены!
            </div>
        </div>
    <?}?>
    <div class="col-md-12">

            <table class="table table-striped mt-2 interactiveTable">
                <tr>
                    <th></th>
                    <th>Отдел</th>
                    <th>Сотрудники</th>
                </tr>
                <? while (($row = $result_set->fetch_assoc()) != false) { ?>

                    <?php $result_set_users = $db->getDepUsers($row['id']); ?>
                    <tr>
                        <td><input type="radio" name="idItem" value="<?=$row['id']?>"></td>
                        <td style="width: 20%"><?=$row['usergroup_name']?></td>

                        
                        <td style="width: 80%">
                            <ul style="list-style: none;padding:0;">
                               
                            <? while (($row3 = $result_set_users->fetch_assoc()) != false) { ?>
                                 <li>
                                <?=$row3[name]?> 
                                </li>
                            <?}?>
                            
                            </ul></td>
                    </tr>
                <?}?>
            </table>
        </form>
    </div>
<?  require_once '../partsOfPages/footer.php';?>

</div>
<?  require_once '../dashbord/dashbord_footer.php';?> 