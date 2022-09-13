<?php if($_settings->chk_flashdata('success')): 
	
	include_once 'db.php';

// Get status message
if(!empty($_GET['status'])){
    switch($_GET['status']){
        case 'succ':
            $statusType = 'alert-success';
            $statusMsg = 'Members data has been imported successfully.';
            break;
        case 'err':
            $statusType = 'alert-danger';
            $statusMsg = 'Some problem occurred, please try again.';
            break;
        case 'invalid_file':
            $statusType = 'alert-danger';
            $statusMsg = 'Please upload a valid CSV file.';
            break;
        default:
            $statusType = '';
            $statusMsg = '';
    }
}
	
	
	
	?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>
<div class="col-lg-12">
	<div class="card card-outline card-primary">
		<div class="card-header">

<!-- Import & Export link -->
<div class="col-md-12 head">
        <div class="float-left">
		<a href="people/export.php" class="btn btn-primary"><i></i> Export</a>
            <a href="javascript:void(0);" class="btn btn-success" onclick="formToggle('importFrm');"><i class="plus"></i> Import</a>
        </div>
    </div>
    <!-- CSV file upload form -->
    <div class="col-md-12" id="importFrm" style="display: none;">
        <form action="people/importData.php" method="post" enctype="multipart/form-data">
            <input type="file" name="file" />
            <input type="submit" class="btn btn-primary" name="importSubmit" value="IMPORT">
        </form>
    </div>




			<div class="card-tools">
				<a class="btn btn-block btn-sm btn-default btn-flat border-primary new_people" href="javascript:void(0)"><i class="fa fa-plus"></i> Add New</a>
			</div>
		</div>
		<div class="card-body">
			<table class="table tabe-hover table-bordered" id="list">
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th>Code</th>
						<th>Name</th>
						<th>Email</th>
						<th>Contact</th>
						<th>Address</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$qry = $conn->query("SELECT p.*,z.name as zname,c.name as cname, s.name as sname FROM people p inner join barangay_list z on z.id = p.zone_id inner join city_list c on c. id= z.city_id inner join state_list s on s.id = c.state_id order by z.name asc ");
					while($row= $qry->fetch_assoc()):
					?>
					<tr>
						<th class="text-center"><?php echo $i++ ?></th>
						<td><b><?php echo $row['code'] ?></b></td>
						<td><b><?php echo ucwords($row['lastname'].", ".$row['firstname'].' '.$row['middlename']) ?></b></td>
						<td><b><?php echo $row['email'] ?></b></td>
						<td><b><?php echo $row['contact'] ?></b></td>
						<td><b><?php echo ucwords($row['address'].', '.$row['zname'].', '.$row['cname'].' City, '.$row['sname']) ?></b></td>
						<td class="text-center">
		                    <div class="btn-group">
		                    	<a href="javascript:void(0)" data-id='<?php echo $row['id'] ?>' class="btn btn-success btn-track track_people">
		                          <i class="fas fa-list"></i>
		                        </a>
		                    	<a href="javascript:void(0)" data-id='<?php echo $row['id'] ?>' class="btn btn-info btn-flat view_people">
		                          <i class="fas fa-eye"></i>
		                        </a>
		                        <a href="javascript:void(0)" data-id='<?php echo $row['id'] ?>' class="btn btn-primary btn-flat manage_people">
		                          <i class="fas fa-edit"></i>
		                        </a>
		                        <button type="button" class="btn btn-danger btn-flat delete_people" data-id="<?php echo $row['id'] ?>">
		                          <i class="fas fa-trash"></i>
		                        </button>
	                      </div>
						</td>
					</tr>	
				<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
function formToggle(ID){
    var element = document.getElementById(ID);
    if(element.style.display === "none"){
        element.style.display = "block";
    }else{
        element.style.display = "none";
    }
}
	$(document).ready(function(){
		$('#list').dataTable()
		$('.new_people').click(function(){
			uni_modal("New Individual","./people/manage.php",'mid-large')
		})
		$('.manage_people').click(function(){
			uni_modal("Manage Individual","./people/manage.php?id="+$(this).attr('data-id'),'mid-large')
		})
		$('.track_people').click(function(){
			uni_modal("Tracks","./people/tracks.php?id="+$(this).attr('data-id'),'mid-large')
		})
		$('.view_people').click(function(){
			uni_modal("Person's CTS Card","./people/card.php?id="+$(this).attr('data-id'))
		})
		$('.delete_people').click(function(){
		_conf("Are you sure to delete this Individual?","delete_people",[$(this).attr('data-id')])
		})
		$('#list').dataTable()
	})
	function delete_people($id){
		start_loader()
		$.ajax({
			url:_base_url_+'classes/People.php?f=delete',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					location.reload()
				}
			}
		})
	}
</script>