<template>
    <page-load id="speedboost">
        <page-title>
	    <img :src="image_path_by_name('item', 'blossom bag')" alt="" class="margin-right-5">
            Cap
            <span class="margin-left-45">Calculator</span>
        </page-title>

        <div class="alert alert-warning">
            <center> NOTE: This calculator is experimental and imbuement % can change whenever since this is not live yet.</center>
        </div>

	<div class="row">
	    <div class="col-md-12">
		<panel>
			<center><b>This calculator will show you the bonus cap you will get from the featherweight imbuement.</b></center>
		</panel>
	    </div>
	</div>
	
	<div class="row">
	<div class="col-md-12">
		<panel class="form">

			<div class="row">
				<div class="col-md-3"></div>
				<div class="col-md-6">
					<center>
					<el-radio class="radio" v-model="voc" :label="1">
							Knight
					</el-radio>
					<el-radio class="radio" v-model="voc" :label="2">
							Paladin
					</el-radio>
					<el-radio class="radio" v-model="voc" :label="3">
							Druid
					</el-radio>
					<el-radio class="radio" v-model="voc" :label="4">
							Sorcerer
					</el-radio>
					</center>
				<div class="col-md-3"></div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-3"></div>

				<div class="col-md-6">
					<form-input placeholder="level" v-model="level" style="text-align:center;" />
				</div>

				<div class="col-md-3"></div>
			</div>

			<div class="row">
				<div class="col-md-3"></div>
				<div class="col-md-6">
					<center>
					<el-radio class="radio" v-model="capperc" :label="5">
							4%
					</el-radio>
					<el-radio class="radio" v-model="capperc" :label="6">
							8%
					</el-radio>
					<el-radio class="radio" v-model="capperc" :label="7">
							12%
					</el-radio>
					</center>
				<div class="col-md-3"></div>
				</div>
			</div>

		</panel>

		<panel class="shareexp" v-if="capcalc">
			<center>You will get <b>{{ capcalc.toFixed() }}</b> extra cap from this imbuement.</center>
		</panel>
	</div>
	</div>

    </page-load>
</template>

<script type="text/babel">

export default {
    data () {
	return {
		capperc: '',
		level: '',
		voc: '',
	}
},

computed: {

		voccalc() {
			const voc = parseInt(this.voc)
			switch (voc) {
				case 0:
				   return (this.level * 0)
				case 1:
				   return ((this.level - 8) * 25 + 470)
				case 2:
				   return ((this.level - 8) * 20 + 470)
				case 3:
				   return ((this.level - 8) * 10 + 470)
				case 4:
				   return ((this.level - 8) * 10 + 470)
					}
			},

		capcalc() {
			const capperc = parseInt(this.capperc)
			switch (capperc) {
				case 0:
				   return (this.voccalc * 0)
				case 5:
				   return (this.voccalc * 0.04)
				case 6:
				   return (this.voccalc * 0.08)
				case 7:
				   return (this.voccalc * 0.12)
	  				}
			}

	}
}
</script>













