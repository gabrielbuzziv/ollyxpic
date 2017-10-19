<template>
    <page-load>
		<page-title>
			<img :src="image_path('item', 357)" class="margin-right-10">
			Change Log
			<span>All about OllyxPic</span>
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