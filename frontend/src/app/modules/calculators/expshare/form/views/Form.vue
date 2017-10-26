<template>
    <page-load id="speedboost">
        <page-title>
	    <img :src="image_path_by_name('item', 'purple tome')" alt="" class="margin-right-5">
            Exp
            <span class="margin-left-45">Share</span>
        </page-title>

	<div class="col-md-12">
		<panel class="form">

			<div class="row">
				<div class="col-md-3"></div>

				<div class="col-md-6">
					<center><b>This calculator will show you what minimum/maximum level another player has to be to share exp with you.</b></center>
				</div>

				<div class="col-md-3"></div>
			</div>

			<div class="row">
				<div class="col-md-3"></div>

				<div class="col-md-6">
					<form-input placeholder="Level" v-model="level" style="text-align:center;" />
				</div>

				<div class="col-md-3"></div>
			</div>
		</panel>

		<panel class="shareexp" v-if="level">
			<center>minimum level to share exp with you: <b>{{ minShare.toFixed() }}</b> And maximum level to share with you: <b>{{ maxShare.toFixed() }}</b></center>
		</panel>
	</div>
    </page-load>
</template>

<script type="text/babel">

export default {
data () {
	return {
		level: ''
	}
},

computed: {
		minShare () {
			return parseInt(this.level) / 3 * 2;
		},

		maxShare () {
			return parseInt(this.level) * 3 / 2;
		},
	}
}




</script>













