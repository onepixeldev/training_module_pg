<form class="form-horizontal" method="post">
    <div class="modal-header btn-primary">
        <h4 class="modal-title" id="myModalLabel">Add New Training Assessment Setup</h4>
    </div>
    <div class="modal-body">
        <div id="alert">
        	<b>Note : </b> ( <b><font color="red">*</font></b> ) <b><font color="red">compulsory fields</font></b><br>&nbsp; <span id="note"></span>
    	</div>
        <div class="form-group">
            <label class="col-md-3 control-label"><b>Type</b> <b><font color="red">* </font></b></label>
			<div class="col-md-3">
				<select class="selectpicker form-control" name="form[type]">
					<option value="" selected > ---Please select--- </option>
					<option class="text-primary" value="FACILITATOR">FACILITATOR</option>
					<option class="text-success" value="TRAINING">TRAINING</option>
				</select>
			</div>
        </div>
        <div class="form-group">
            <label class="col-md-3 control-label"><b>Order</b> <b><font color="red">* </font></b></label>
            <div class="col-md-3">
				<input name="form[order]" placeholder="Sort priority by number" class="form-control" type="text">
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-3 control-label"><b>Code</b> <b><font color="red">* </font></b></label>
            <div class="col-md-3">
				<input name="form[code]" placeholder="Assessment code" class="form-control" type="text">
            </div>
        </div>
		<div class="form-group">
            <label class="col-md-3 control-label"><b>Description</b> <b><font color="red">* </font></b></label>
            <div class="col-md-9">
				<input name="form[description]" placeholder="Assessment description" class="form-control" type="text">
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-3 control-label"><b>Label</b></label>
            <div class="col-md-3">
				<input type="text" name="form[label]" placeholder="Label" class="form-control">
            </div>
        </div>
        <div class="form-group">
			<label class="col-md-3 control-label"><b>Assessment Type</b> <b><font color="red">* </font></b></label>
			<div class="col-md-3">
				<select class="selectpicker form-control" name="form[assessment_type]">
					<option value="" selected > ---Please select--- </option>
					<option class="text-primary" value="OBJECTIVE">OBJECTIVE</option>
					<option class="text-success" value="SUBJECTIVE">SUBJECTIVE</option>
				</select>
			</div>
		</div>
        <div class="form-group">
			<label class="col-md-3 control-label"><b>Status</b> <b><font color="red">* </font></b></label>
			<div class="col-md-3">
				<?php echo form_dropdown('form[status]', array('' => ' ---Please select--- ','Y'=>'ACTIVE','Y'=>'ACTIVE','N'=>'INACTIVE'), '', 'class="form-control" style="width:50%"')?>
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
			msg.wait('#alertFooter');
			
			$('.btn').attr('disabled', 'disabled');
			$.ajax({
				type: 'POST',
				url: '<?php echo $this->lib->class_url('saveTASV')?>',
				data: data,
				dataType: 'JSON',
				success: function(res) {
					msg.show(res.msg, res.alert, '#alert');
					//msg.show(res.msg, res.alert, '#alertFooter');
					
					if (res.sts == 1) {
						setTimeout(function () {
							location = '<?php echo $this->lib->class_url('viewTabFilter','s11')?>';
						}, 1000);
					} else {
						$('.btn').removeAttr('disabled');
					}
				},
				error: function() {
					$('.btn').removeAttr('disabled');
					msg.danger('Please contact administrator.', '#alert');
					//msg.danger('Please contact administrator.', '#alertFooter');
				}
			});	
		});  				

	});  	  
</script>