<div class="referral-outer-container">
	<div class="inner-container">
		

		<div class="validation-errors hidden">
			<p>That is not a valid email address</p>
		</div>
		<div class="header">
			<div class="left">
				<p>Here you can send out referrals to your friends. Click the button on the right and enter in the person's email address you would like to refer.</p>
			</div>
			<div class="right">
				<p><span class="referral-button">Refer a friend</span></p>
			</div>
			<div style="clear:both"></div>
		</div>

		<div class="email-container hidden">
			<input id="referral_email" type="text" />
			<input id="referral_amount" type="hidden" value="-1" />
			<input id="referrer" type="hidden" value="<?php echo $referrer ?>" />
			<input id="admin_referral_amount" type="hidden" value="<?php echo count($referrals);?>" />
			<span id="submit_referral">Submit</span>
			<span id="cancel_referral">Cancel</span>
		</div>

		<div class="referrals">

			<div class="referral-header">
				<p>You have sent out <?php echo count($referrals); ?> referrals.</p>
			</div>

			<?php foreach($referrals as $referal): ?>

				<div class="referral-container">
					<p class='referral'><?php echo $referal['email']; ?></p>
				</div>

			<?php endforeach; ?>
			
		</div>

	</div>
</div>