<?php include("header.php"); ?>

	
<?php

if(isset($_GET['backup']))
{
$dir = dirname(__FILE__) . '\backup\dump'.date("Y-m-d").'.sql';
$mysql_path = 'C:\xampp\mysql\bin\\';

exec("{$mysql_path}mysqldump --user={$user} --password={$pass} --host={$host} {$database} --result-file={$dir} 2>&1", $output);
//var_dump($output);
}

?>	 
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card p-card">
                            <div class="header">
								<div class="row">
									<div class="col-md-9 col-xs-7">
										<h4 class="title">Database Backup</h4>
									</div>
									<div class="col-md-3 col-xs-5 text-right">
										<a href="backup.php?backup=backup" class="btn btn-primary btn-fill">Store Backup</a>
									</div>
								</div>
                            </div>
							<?php
										if(isset($_GET['backup'])){
											 
							?>					
							 <div class="alert alert-success">Database Backup Is Successfully Taken!</div>
								 
												
								<?php		
										}

								
								?>
                            <div class="table-responsive table-full-width">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <th>Database</th>
                                    	<th>Restore</th>
                                    	
                                    </thead>
                                    <tbody>
                                        <?php 

									$dir = "backup/";

									// Sort in ascending order - this is default
									$databases = scandir($dir);
									

									foreach($databases as $row){
										if($row == "." || $row == "..")
												continue;
											else
									?>

									<tr>
										<td>
											<?php 
											
											echo $row; ?>
										</td>
										<td>
												<a onclick="return confirm('Are you sure you want to restore this database ?');" href="backup.php?database=<?= $row?>" class="btn btn-danger btn-fill b_padding"><i class="fa fa-reply-all"></i></a>
											</td>

									</tr>

									<?php } ?>
                                        
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <?php
include("footer.php"); ?>
    