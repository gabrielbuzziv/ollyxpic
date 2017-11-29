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
							3%
					</el-radio>
					<el-radio class="radio" v-model="capperc" :label="6">
							8%
					</el-radio>
					<el-radio class="radio" v-model="capperc" :label="7">
							15%
					</el-radio>
					</center>
				<div class="col-md-3"></div>
				</div>
			</div>



		</panel>

		<panel class="shareexp" v-if="capcalc && level > 7">
			<center><b>CAP INFO</b></center>
			<center>Base cap : <b>{{ voccalc.toFixed() }}</b></center>
			<center>Bonus cap : <b>{{ capcalc.toFixed() }}</b></center>
			<center>Total cap: <b>{{ totalcap.toFixed() }}</b></center>	
		</panel>

		<panel class="shareexp" v-if="capcalc && level > 7">
			<center><b>BONUS INFO</b></center>
			<center> HP: <b>{{ totalhp }}</b></center>
			<center> MP: <b>{{ totalmp }}</b></center>
		</panel>
	</div>
	</div>

    </page-load>
</template>

<script type="text/babel">

export default {
    data () {
	return {
		total: '',
		capperc: '',
		level: '',
		voc: '',
		hp: '',
		mp: '',
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
				   return (this.voccalc * 0.03)
				case 6:
				   return (this.voccalc * 0.08)
				case 7:
				   return (this.voccalc * 0.15)
	  				}
			},
		totalcap() {
			const voc = parseInt(this.voc)
			switch (voc) {
				case 1:
				   return (this.capcalc + this.voccalc)
				case 2:
				   return (this.capcalc + this.voccalc)
				case 3:
				   return (this.capcalc + this.voccalc)
				case 4:
				   return (this.capcalc + this.voccalc)
				       }
			},
		totalhp() {
			const voc = parseInt(this.voc)
			switch (voc) {
				case 1:
				   return ((this.level - 8) * 15 + 185)
				case 2:
				   return ((this.level - 8) * 10 + 185)
				case 3:
				   return ((this.level - 8) * 5 + 185)
				case 4:
				   return ((this.level - 8) * 5 + 185)
				       }
			},
		totalmp() {
			const voc = parseInt(this.voc)
			switch (voc) {
				case 1:
				   return ((this.level - 8) * 5 + 90)
				case 2:
				   return ((this.level - 8) * 15 + 90)
				case 3:
				   return ((this.level - 8) * 30 + 90)
				case 4:
				   return ((this.level - 8) * 30 + 90)
				       }
			}

	}
}
</script>













