<template>
    <page-load id="speedboost">
        <page-title>
	    <img :src="image_path_by_name('item', 'blossom bag')" alt="" class="margin-right-5">
            Cap
            <span class="margin-left-45">Calculator</span>
        </page-title>

	<div class="col-md-12">

        <div class="alert alert-warning">
            <center> NOTE: This calculator is experimental and imbuement % can change whenever since this is not live yet.</center>
        </div>

		<panel class="form">
			<div class="row">
				<div class="col-md-3"></div>

				<div class="col-md-6">
					<center><b>This calculator will show you the bonucs cap you will get from the featherweight imbuement</b></center>
				</div>

				<div class="col-md-3"></div>
			</div>

			<div class="row">
				<div class="col-md-3"></div>

				<div class="col-md-6">
					<form-input placeholder="cap" v-model="cap" style="text-align:center;" />
				</div>

				<div class="col-md-3"></div>
			</div>

			<div class="row">
				<div class="col-md-3"></div>
				<div class="col-md-6">
					<center>
					<el-radio class="radio" v-model="capperc" :label="1">
							4%
					</el-radio>
					<el-radio class="radio" v-model="capperc" :label="2">
							8%
					</el-radio>
					<el-radio class="radio" v-model="capperc" :label="3">
							12%
					</el-radio>
					</center>
				<div class="col-md-3"></div>
				</div>
			</div>
		</panel>
	</div>


		<panel class="shareexp" v-if="capcalc">
			<center>You will get <b>{{ capcalc }}</b> extra cap from this imbuement.</center>
		</panel>

    </page-load>
</template>

<script type="text/babel">

export default {
    data () {
	return {
		capperc: '',
		cap: '',
	}
},

computed: {

		capcalc() {
			const capperc = parseInt(this.capperc)
			switch (capperc) {
				case 0:
				   return (this.cap * 0)
				case 1:
				   return (this.cap * 0.04)
				case 2:
				   return (this.cap * 0.08)
				case 3:
				   return (this.cap * 0.12)
	  	}
	}
}
}
</script>













