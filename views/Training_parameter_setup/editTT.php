<form class="form-horizontal" method="post">
    <div class="modal-header btn-success">
        <h4 class="modal-title" id="myModalLabel">Edit Record</h4>
    </div>
    <div class="modal-body">
        <div id="alert">
        	<b>Note : </b> ( <b><font color="red">*</font></b> ) <b><font color="red">compulsory fields</font></b><br>&nbsp; <span id="note"></span>
    	</div>
        <div class="form-group">
            <label class="col-md-3 control-label"><b>Code</b></label>
            <div class="col-md-8">
				<input name="form[code]" placeholder="Training type code" class="form-control" type="text" value="<?php echo $tt_code?>" readonly>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-3 control-label"><b>Training Type</b> <b><font color="red">* </font></b></label>
            <div class="col-md-8">
				<input name="form[training_type]" placeholder="Training type name" class="form-control" type="text" value="<?php echo $tt_desc->TT_DESC?>">
            </div>
        </div>
		<div class="form-group">
			<label class="col-md-3 control-label font-weight-bold"><b>Counted </b><b><font color="red">* </font></b></label>
			<div class="col-md-2">
				<?php echo form_dropdown('form[counted]', array('Y'=>'YES','N'=>'NO'), $tt_desc->TT_COUNTED, 'class="selectpicker form-control width-50"')?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label font-weight-bold"><b>Training Evaluation </b><b><font color="red">* </font></b></label>
			<div class="col-md-2">
				<?php echo form_dropdown('form[training_evaluation]', array('Y'=>'YES','N'=>'NO'), $tt_desc->TT_EVALUATION, 'class="selectpicker form-control width-50"')?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label font-weight-bold"><b>Confirmation </b><b><font color="red">* </font></b></label>
			<div class="col-md-2">
				<?php echo form_dropdown('form[confirmation]', array('Y'=>'YES','N'=>'NO'), $tt_desc->TT_CONFIRMATION, 'class="selectpicker form-control width-50"')?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-3 control-label font-weight-bold"><b>Service Book </b><b><font color="red">* </font></b></label>
			<div class="col-md-2">
				<?php echo form_dropdown('form[service_book]', array('Y'=>'YES','N'=>'NO'), $tt_desc->TT_SERVICE_BOOK, 'class="selectpicker form-control width-50"')?>
			</div>
		</div>
    </div>
    <div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-hand-o-left"></i> Close</button>
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
    </div>
</form>

<script>
	$(document).ready(function(){	
		$('form').submit(function (e) { 
			e.preventDefault();
			var data = $('form').serialize();	
			msg.wait('#alert');
			
			$('.btn').attr('disabled', 'disabled');
			$.ajax({
				type: 'POST',
				url: '<?php echo $this->lib->class_url('updateTT')?>',
				data: data,
				dataType: 'JSON',
				success: function(res) {
					msg.show(res.msg, res.alert, '#alert');
					msg.show(res.msg, res.alert, '#alertWarning');

					if (res.sts == 1) {
						setTimeout(function () {
							location = '<?php echo $this->lib->class_url('viewTabFilter','s1')?>';
						}, 1000);
					} else {
						$('.btn').removeAttr('disabled');
					}
				},
				error: function() {
					$('.btn').removeAttr('disabled');
					msg.danger('Please contact administrator.', '#alert');
					msg.danger('Please contact administrator.', '#alertWarning');
				}
			});			
		});  				

	});
</script>