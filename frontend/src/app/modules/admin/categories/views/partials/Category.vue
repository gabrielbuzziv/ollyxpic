<template>
    <tr>
        <td>
            <img :src="image_path_by_name('item', category.image)" v-if="category.image">
        </td>

        <td>
            {{ category.title }}
        </td>

        <td>
            <el-checkbox :checked="!! category.usable" @change="toggleUsable"></el-checkbox>
        </td>

        <td class="text-center">
            {{ category.slot || '-' }}
        </td>

        <td class="text-right">
            <router-link :to="{ name: 'admin.categories.edit', params: { id: category.id } }"
                class="btn btn-xs">
                <i class="mdi mdi-pencil-circle"></i>
            </router-link>
        </td>
    </tr>
</template>

<script>
    export default {
        props: ['category'],

        methods: {
            toggleUsable () {
                this.$store.dispatch('categories/TOGGLE_USABLE', this.category.id)
                    .then(() => this.$store.dispatch('categories/FETCH_CATEGORIES'))
            }
        }
    }
</script>