<style>

	.eedc-home-wrap {
		padding:35px 0;
		display:flex;
		flex-direction: column;
	}

	.eedc-home-wrap .header-wrap {
		background:#222;
		display:flex;
		justify-content: space-between;
		color:white;
		align-items:center;
		padding:25px;
		border-radius:4px;
	}

	.content-wrap {
		padding:25px;
		margin-top:15px;
	}

	.content-wrap p,
	.content-wrap li,
	.content-wrap a {
		font-size:16px;
	}

	.content-wrap h2 {
		margin-top:45px;
	}

</style>

<div class="eedc-home-wrap">

	<div class="header-wrap">
		<a href="https://editorenhancer.com" target="_blank">
			<img src="https://editorenhancer.com/wp-content/uploads/sites/34/logo.png" alt="Editor Enhancer Logo">
		</a>
		Version <?php echo $this->version; ?>
	</div>

	<div class="content-wrap">
		<?php require_once 'tabs.php'; ?>
		<h1>Welcome to Dashboard Customizer by Editor Enhancer!</h1>
		<p>Great things await! This plugin lets you design custom dashboards for your users and clients. To get started:</p>
		<ol>
			<li><strong>Activate your license key.</strong> You'll see it when you <a href="https://editorenhancer.com/login" target="_blank">log in to your account</a> on the Editor Enhancer website.</li>
			<li><strong>Choose your preferences.</strong> You may want to modify a couple things first.</li>
			<li><strong>Add a new dashboard.</strong> Using the tabs at the top, click "New Dashboard". Then, define its settings.</li>
			<li><strong>Launch Oxygen and design your custom dashboard!</strong></li>
		</ol>
		<h2>Resources</h2>
		<a href="https://editorenhancer.com/newsletter-signup">Sign up for the Editor Enhancer newsletter to hear about betas and upcoming features</a><br><br>
		<a href="https://www.facebook.com/groups/editorenhancer/" target="_blank">Join the official Editor Enhancer user group on Facebook</a><br><br>
		<a href="https://trello.com/b/JFxiPTa5" target="_blank">Follow requests and bug reports on Trello</a><br><br>
	</div>
</div>
