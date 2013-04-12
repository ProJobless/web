	</div>

	<div id="footer">
		<p>© 2013 John Sparwasser</p>
	</div>

</div>

<script type="text/javascript" src="<?php echo base_url();?>scripts/jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>scripts/Equal-Heights.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>scripts/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>scripts/jquery.Jcrop.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>scripts/mashtagg_globals.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>scripts/mashtagg.js"></script>

<!-- Custom javascript that we might want to include -->
<?php
if (isset($javascript_includes)) {
	foreach ($javascript_includes as $javascript) {
		echo $javascript;
	}
}
?>
	

</body>

</html>