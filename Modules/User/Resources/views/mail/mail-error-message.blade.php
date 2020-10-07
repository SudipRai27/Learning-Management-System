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
	
</style>
<div class="error-wrapper">
	<div class="error-inner">
		<div class="error-text">
			Sorry this request could not be processed ! <br>
			{{$error}}
		</div>		
	</div>
</div>