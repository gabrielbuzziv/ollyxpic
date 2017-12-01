<template>
    <page-load>
		<page-title>
			<img :src="image_path_by_name('item', 'scroll of heroic deeds')" class="margin-right-10">
			<div class="title">
				<h2>Change Log</h2>
				<span>All about OllyxPic</span>
			</div>
		</page-title>

	
        <panel :title="change.title" v-for="change in changes" :key="change.id">
			<div v-html="change.body"></div>
		</panel>
    </page-load>
</template>

<script>
	import services from '../services'

	export default {
	    data () {
	        return {
	            changes: []
			}
		},

		mounted () {
	        services.getChanges()
				.then(response => {
				    this.changes = response.data
				})
		}
	}
</script>