<style type="text/css">
	* {
		margin: 0;
		padding:0;
	}
	.error-wrapper {
		height: 100vh;
		background: #fcf2f2;
		
	}

	.error-inner {
		display: flex;
		align-items: center;
		justify-content: center;
		flex-direction: column;
		height: 100%;		
	}

	.error-text {
		font-size: 1.4rem;
	}

	.back-button {
		background: #e96b24;
		outline: none;
		padding: 0.5rem;
		margin:1rem;
		font-size: 1.2rem;
		display: inline-block;
		text-decoration: none;
		color: #f4f4f4;
		transition: all 0.5s ease;
	}

	.back-button:hover {
		transform: scale(1.1);
		opacity: 0.8;
	}

</style>
<div class="error-wrapper">
	<div class="error-inner">
		<div class="error-text">
			403 | Insufficient Permission to access this page or to perform this action
			
		</div>
		<div class="error-button">
			<a href="javascript: history.go(-1)" class="back-button">Go Back</a>
		</div>
	</div>
</div>