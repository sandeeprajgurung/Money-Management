<?php

$msgBox='';
//Include Functions
include('includes/Functions.php');

//Include Notifications
include ('includes/notification.php');

// Get all Income
$GetAllIncome 	 = "SELECT SUM(Amount) AS Amount FROM assets WHERE UserId = $UserId";
$GetAIncome		 = mysqli_query($mysqli, $GetAllIncome);
$IncomeCol 		 = mysqli_fetch_assoc($GetAIncome);


// Get all Expense
$GetAllExpense   = "SELECT SUM(Amount) AS Amount FROM bills WHERE UserId = $UserId";
$GetAExpense         = mysqli_query($mysqli, $GetAllExpense);
$ExpenseCol          = mysqli_fetch_assoc($GetAExpense);

//Count current totals Income
$CountTotals = $IncomeCol['Amount'] - $ExpenseCol['Amount'];

//Get Recent Income History
$GetIncomeHistory = "SELECT * from assets left join category on assets.CategoryId = category.CategoryId left join account on assets.AccountId = account.AccountId where assets.UserId = $UserId ORDER BY assets.Date DESC LIMIT 10";
$IncomeHistory = mysqli_query($mysqli,$GetIncomeHistory); 

//Get Recent Expense History
$GetExpenseHistory = "SELECT * from bills left join category on bills.CategoryId = category.CategoryId left join account on bills.AccountId = account.AccountId where bills.UserId = $UserId ORDER BY bills.Dates DESC LIMIT 10";
$ExpenseHistory = mysqli_query($mysqli,$GetExpenseHistory); 


// Get all by month Income
$GetAllIncomeDate 	 = "SELECT SUM(Amount) AS Amount FROM assets WHERE UserId = $UserId AND MONTH(Date) = MONTH (CURRENT_DATE())";
$GetAIncomeDate		 = mysqli_query($mysqli, $GetAllIncomeDate);
$IncomeColDate 		 = mysqli_fetch_assoc($GetAIncomeDate);

// Get all by month Expense
$GetAllExpenseDate 	 = "SELECT SUM(Amount) AS Amount FROM bills WHERE UserId = $UserId AND MONTH(Dates) = MONTH (CURRENT_DATE())";
$GetAExpenseDate		 = mysqli_query($mysqli, $GetAllExpenseDate);
$ExpenseColDate 		 = mysqli_fetch_assoc($GetAExpenseDate);

// Get all  Income
$GetAllIncomeOverall    = "SELECT SUM(Amount) AS Amount FROM assets WHERE UserId = $UserId" ;
$GetAIncomeOverall      = mysqli_query($mysqli, $GetAllIncomeOverall);
$IncomeColOverall       = mysqli_fetch_assoc($GetAIncomeOverall);
$IncomeOverall          = $IncomeColOverall['Amount'];

// Get all Expense
$GetAllBillsOverall    = "SELECT SUM(Amount) AS Amount FROM bills WHERE UserId = $UserId ";
$GetABillsOverall      = mysqli_query($mysqli, $GetAllBillsOverall);
$BillsColOverall       = mysqli_fetch_assoc($GetABillsOverall);
$BillsOverall          = $BillsColOverall['Amount'];

// Budget Progress
$Getbudgets = "SELECT AmountIncome As Amount, (AmountIncome - AmountExpense) As Totals, AmountExpense/(AmountIncome - AmountExpense) * 100/100 AS Per,CategoryName
					  FROM ( SELECT  UserId,CategoryId, 
                      SUM(Amount) AS AmountExpense
                      FROM bills
				      GROUP BY CategoryId) AS b
					  LEFT JOIN ( SELECT  CategoryId,
                      SUM(Amount) AmountIncome
				      FROM budget WHERE MONTH(Dates) = MONTH (CURRENT_DATE())
					  GROUP BY CategoryId) AS a ON b.CategoryId = a.CategoryId
                      LEFT JOIN (SELECT CategoryId, CategoryName 
                      FROM category
                      GROUP BY CategoryId) AS c
					  ON b.CategoryId = c.CategoryId WHERE b.UserId = $UserId";
$Budgets = mysqli_query($mysqli, $Getbudgets);


//Include Global page
	include ('includes/global.php');
?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo $Dashboard;?></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="glyphicon glyphicon-calendar fa-4x"></i>
                                </div>
                                <div class="col-xs-12 text-left">
                                    <h2><?php echo $ColUser['Currency'].' '.number_format($ExpenseColDate['Amount']); ?></h2>
                                    <div><?php echo $CurrentExpense;?></div>
                                </div>
                            </div>
                        </div>
                        <a href="#">
                            <div class="panel-footer">
                                <span class="pull-left"></span>
                                <span class="pull-right"></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="glyphicon glyphicon-calendar fa-4x"></i>
                                </div>
                                <div class="col-xs-12 text-left">
                                    <h2><?php echo $ColUser['Currency'].' '.number_format($IncomeColDate['Amount']); ?></h2>
                                    <div><?php echo $CurrentIncome;?></div>
                                </div>
                            </div>
                        </div>
                        <a href="#">
                            <div class="panel-footer">
                                <span class="pull-left"></span>
                                <span class="pull-right"></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="glyphicon glyphicon-resize-full fa-4x"></i>
                                </div>
                                <div class="col-xs-12 text-left">
                                    <h2><?php echo $ColUser['Currency'].' '.number_format($ExpenseCol['Amount']);?> </h2>
                                    <div><?php echo $TotalExpenseDashboard;?></div>
                                </div>
                            </div>
                        </div>
                        <a href="#">
                            <div class="panel-footer">
                                <span class="pull-left"></span>
                                <span class="pull-right"></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-1">
                                    <i class="glyphicon glyphicon-resize-small fa-4x"></i>
                                </div>
                                <div class="col-xs-12 text-left">
                                    <h2><?php echo $ColUser['Currency'].' '.number_format($CountTotals);?> </h2>
                                    <div><?php echo $CurrentBalance;?></div>
                                </div>
                            </div>
                        </div>
                        <a href="#">
                            <div class="panel-footer">
                                <span class="pull-left"></span>
                                <span class="pull-right"></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i> <?php echo $TenIncome;?>
                            
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                           <div>
								<div class="table-responsive">
                                        <table class="table table-bordered table-hover table-striped">
                                            <thead>
                                                <tr>
                                                    <th><?php echo $Title;?></th>
                                                    <th><?php echo $Date;?></th>
                                                    
                                                    <th><?php echo $Account;?></th>
                                                    
                                                    <th><?php echo $Amount;?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                 <?php while($col = mysqli_fetch_assoc($IncomeHistory)){ ?>
												<tr>
													<td><?php echo $col['Title'];?></td>
													<td><?php echo date("M d Y",strtotime($col['Date']));?></td>
													
													<td><?php echo $col['AccountName'];?></td>
													
													<td><?php echo $ColUser['Currency'].' '.number_format($col['Amount']);?></td>
                                                </tr>
                                               <?php } ?>   
                                            </tbody>
                                        </table>
								</div>
                           <div class="text-center"><a href="index.php?page=AssetReport"><?php echo $ViewDetails;?></a></div>
                           </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    
                    <!-- /.panel -->
                    
                </div>
                <!-- /.col-lg-8 -->
                <div class="col-lg-6">
                 <div class="panel panel-red">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i> <?php echo $TenExpense;?>
                            
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-striped">
                                        <thead>
                                                <tr>
                                                    <th><?php echo $Title;?></th>
                                                    <th><?php echo $Date;?></th>
                                                    
                                                    <th><?php echo $Account;?></th>
                                                    
                                                    <th><?php echo $Amount;?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                 <?php while($cols = mysqli_fetch_assoc($ExpenseHistory)){ ?>
                                                <tr>
                                                    <td><?php echo $cols['Title'];?></td>
                                                    <td><?php echo date("M d Y",strtotime($cols['Dates']));?></td>
                                                    
                                                    <td><?php echo $cols['AccountName'];?></td>
                                                    
                                                    <td><?php echo $ColUser['Currency'].' '.number_format($cols['Amount']);?></td>
                                                </tr>
                                               <?php } ?>   
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- /.table-responsive -->
                                </div>
                                <div class="text-center"><a href="index.php?page=ExpenseReport"><?php echo $ViewDetails;?></a></div>
                                <!-- /.col-lg-4 (nested) -->
                                
                                <!-- /.col-lg-8 (nested) -->
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- /.panel-body -->
                    </div>

                </div>
                <!-- /.col-lg-4 -->
                
            </div>
            <!-- /.row -->
            <div class="col-lg-12">
                    <!-- /.panel -->
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i> <?php echo $OverallReport; ?>
                        </div>
                        <div class="panel-body">
                            <div id="morris-donut-chart"></div>  
                        </div>
                    </div>
                </div>
                 
        </div>
        <!-- /#page-wrapper -->
        <script>


    $(function() {
		
		$('#calendar').fullCalendar({
			header:{
					left: 'prev,next today',
					center: 'title',
					right: 'month, agendaWeek,agendaDay'
				},
			allDayDefault: true,
			editable: false,
			backgroundColor : '#428bca',
			events:{
					url:'includes/get-daily.php',
					error: function(){
							alert('there was an error while fetching events!');
						
						},
				},		
		eventRender: function(event, element) {
				element.find(".fc-content").remove();
				element.find(".fc-event-time").remove();
				var new_description =   
						event.title + '<br/>'
						+  event.names + '<br/>';
						element.append(new_description);
				},
				
			loading: function(bool){
					$('#loading').toggle(bool);
					
				}		
			
			});
			
			$('#expense-calendar').fullCalendar({
			header:{
					left: 'prev,next today',
					center: 'title',
					right: 'month, agendaWeek,agendaDay'
				},
			allDayDefault: true,
			editable: false,
			backgroundColor : '#428bca',
			events:{
					url:'includes/get-expense-daily.php',
					error: function(){
							alert('there was an error while fetching events!');
						
						},
				},		
		eventRender: function(event, element) {
				element.find(".fc-content").remove();
				element.find(".fc-event-time").remove();
				var new_description =   
						event.title + '<br/>'
						+  event.names + '<br/>';
						element.append(new_description);
				},
				
			loading: function(bool){
					$('#loading').toggle(bool);
					
				}		
			
			});
		
		
		
		Morris.Donut({
        element: 'morris-donut-chart',
        data: [
			
			
            {
            label: "<?php echo $Incomes .' '. $ColUser['Currency'];?>",
            value: <?php echo $IncomeOverall ;?>
            },
            {
            label: "<?php echo $Expenses .' '. $ColUser['Currency'];?>",
            value: <?php echo  $BillsOverall;?>
            },	
       ],
        resize: true
    });

     $('.notification').tooltip({
        selector: "[data-toggle=tooltip]",
        container: "body"
    })

    });
    </script>


</body>

</html>
