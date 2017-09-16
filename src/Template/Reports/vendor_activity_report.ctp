<style>
    #loading-bar-container {
    background: rgba(0, 0, 0, 0.5) none no-repeat scroll 0 0;
    height: 100%;
    left: 0;
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 99999;
    }
    #loading-bar-container .sk-spinner-wave.sk-spinner {
    font-size: 10px;
    height: 30px;
    margin-left: 50%;
    margin-top: 25%;
    text-align: center;
    width: 50px;
}
</style>
<?php
$awardTypes = [
'Manual' => 'manual_awards', 
'Referrals' => 'referral_awards', 
'Tiers' => 'tier_awards', 
'Promotions' => 'promotion_awards', 
'Referral Tiers' => 'referral_tier_awards', 
'Surveys' => 'survey_awards', 
'Coupon'=> 'gift_coupon_awards', 
'Reviews' => 'review_awards',
'MileStone' => 'milestone_level_awards',
'Redemptions' => 'legacy_redemptions',
'Coupon Redemptions' => 'gift_coupon_redemptions'

];

$intervals = [
'This Year' => 'year',
'This Month' => 'month',
'This Week' => 'week',
];
?>

<div id="loading-bar" style="display: none;"><div id="loading-bar-container"><div class="sk-spinner sk-spinner-wave"><div class="sk-rect1"></div><div class="sk-rect2"></div><div class="sk-rect3"></div><div class="sk-rect4"></div><div class="sk-rect5"></div></div></div></div>
<div class="m-b-sm">    
	<?php foreach($intervals as $key=>$value):?>
		<button type='button' class="btn btn-primary interval-btn" onclick="changeInterval('<?=$value?>')" data-value="<?=$value?>");"><?=$key?></button>
	<?php endforeach;?>
</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-content">
					<div>
						<div id="lineChart"></div>
					</div>
					<?php foreach($awardTypes as $key=>$value):?>
						<button type='button' class="btn btn-success data-btn p-r-xsm p-l-xsm m-r-xsm" data-value="<?=$key?>" id="<?=$value?>" onClick = "getData('<?=$value?>', '<?=$key?>');"><?=$key?></button>
						<!-- <div class='col-sm-1'> -->
					<?php endforeach;?>
				</div>
			</div>
		</div>
	</div>

<script type="text/javascript">

	$(document).ready(function(){
		getData('manual_awards', 'Manual');
		$('#manual_awards').addClass('active');
			$('#manual_awards').removeClass('btn-success');
			$('#manual_awards').addClass('btn-info');
        	// console.log(groups);
        });
	$('.data-btn').on('click',function(){
		var button = $(this).attr('data-value');
		if($(this).hasClass('active')){
			$(this).removeClass('active');
			$(this).addClass('btn-success');
			$(this).removeClass('btn-info');
			lineChart.hide(button);
		}else{
			$(this).addClass('active');    
			$(this).removeClass('btn-success');
			$(this).addClass('btn-info');
			lineChart.show(button);
		}
	});
	// $('.interval-btn').on('click',function(){
	// 	alert($(this).attr('data-value'));    
	// });

	var monthNames = ["January", "February", "March", "April", "May", "June","July", "August", "September", "October", "November", "December"];
	var interval = '<?= $interval ?>';
	var max = 5000;
	var awardTypes = <?= json_encode($awardTypes) ?>;
	var groups = [];
	for(award in awardTypes){
		groups.push(award);
	} 
	var data1 = [];
	var xAxis = xAxis;
	var lineChart = false;
	var columns = [];
	function getData(action, id){

		$('#loading-bar').css('display','block');
		$.ajax({
			url: host+"api/reports/vendor-activity-report?action="+action+'&interval='+interval,
			type: "get",
			success:function(data){

				max = data.limit;
				var  newDataArr = [];
                //check if data is empty
                if(typeof data.graphData.length != 'undefined' && data.graphData.length <= 0){

                	graphData = [];

                }else{

                	graphData = data.graphData;
                }

                //check if x axis values are present
                if(!xAxis){

                	xAxis = getXAxis(data.from, data.to);		
                }
                if(!xAxis){
                	return false;
                }
                var arr = [id];
                
                //Create the array
                for(x = 1; x < xAxis.length; x++){

                	if(typeof graphData[xAxis[x]] != "undefined"){
                		arr.push(graphData[xAxis[x]]);       
                	}else{
                		arr.push(0);
                	}	
                }

                //Draw a new chart or load new data into the chart
                if(!lineChart){
                	columns =  [xAxis, arr];
                	drawChart();

                }else{

                	lineChart.load({columns: [arr]});
                }

                $('#loading-bar').css('display','none');
            },
            error:function(data){

            	console.log(data);
            },
            beforeSend: function() {

            }
        });

	}

	function getXAxis(from, to){

		var axis = ['x'];
		from = getJsDate(from);
		to = getJsDate(to);
		switch(interval) {
			

			case 'week':
			case 'month':

			for(x = from; x <= to; x.setDate(x.getDate()+1)){
				axis.push(formatDate(x));
			}

			break;

			case 'year':
			groups = [];
			for(x = from.getMonth(); x <= to.getMonth(); x++){
				year = to.getFullYear();
				axis.push(monthNames[x]+' '+year);
			} 
			break;
			default:
			swal("Error", "Error in interval", 'error');
			return false;
		}
		return axis;
	}

	function formatDate(date){

		var month = monthNames[date.getMonth()];
		// month = month < 10 ? '0'+month : month;
		var day = date.getDate();
		day = day < 10 ? '0'+day : day;
		return month+' '+day+','+date.getFullYear();
	}

	function getJsDate(date){

		date = date.split(' ');
		date = date[0].split('-');
		date = new Date(date[0], date[1]-1, date[2]);
		return date;

	}	

	function changeInterval(newInterval){


		console.log(newInterval);
		window.location = host+'reports/vendor-activity-report?interval='+newInterval;
	}

	function drawChart(){
		lineChart = c3.generate({
			bindto: '#lineChart',
			data: {
				x: 'x',
           // xFormat: '%y%m%d', // 'xFormat' can be used as custom format of 'x'
           columns: columns,
           type: 'bar',
           groups: [groups]
       },
       zoom: {
       	enabled: true
       },
       grid: {
       	y: {
       		lines: [{value:0}]
       	}
       },

       axis: {
       	x: {
       		type: 'category',
       		tick: {
       			rotate: 90,
       			multiline: false,
       		},
       	},
       	y: {
       		max: max,
       		min: 0
       	}
       },
       size: {
       	height: 480
       }
   });
	}



</script>

