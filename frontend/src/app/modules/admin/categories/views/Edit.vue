<template>
    <page-load>
        <page-title>
            <router-link class="btn btn-default pull-right" :to="{ name: 'admin.categories' }">
                <i class="mdi mdi-arrow-left"></i>
            </router-link>

            <img :src="image_path_by_name('item', 'Signed Contract')" class="margin-right-10">
            <div class="title">
                <h2>Category: {{ category.title }}</h2>
                <span>Manage Categories</span>
            </div>
        </page-title>

        <panel>
            <form action="" method="POST" @submit.prevent="onSubmit" ref="form">
                <input type="hidden" name="_method" value="patch">

                <div class="form-group">
                    <label>Slot</label>
                    <el-select class="block" v-model="category.slot" placeholder="Select slot" filterable clearable>
                        <el-option v-for="slot in slots" :value="slot.name" :label="slot.title" :key="slot.name"></el-option>
                    </el-select>

                    <input type="hidden" name="slot" v-model="category.slot">
                </div>

                <div class="form-group">
                    <label>Image Item Name</label>
                    <el-select class="block" v-model="category.image" placeholder="Select image" filterable clearable>
                        <el-option v-for="item in category.items" :value="item.name" :key="item.id">
                            <img :src="image_path('item', item.id)" :title="item.title" height="26" class="margin-right-10">
                            {{ item.title }}
                        </el-option>
                    </el-select>

                    <input type="hidden" name="image" v-model="category.image">
                </div>

                <button class="btn btn-success" :disabled="submiting">
                    <span class="loader inline button margin-right-5" v-if="submiting"></span>
                    <i class="mdi mdi-check margin-right-5" v-else></i>

                    Save
                </button>
            </form>
        </panel>
    </page-load>
</template>

<script>
    import services from '../services'

    export default {
        data () {
            return {
                category: {},
                submiting: false,
                slots: [
                    { name: 'amulet', title: 'Amulet' },
                    { name: 'helmet', title: 'Helmet' },
                    { name: 'backpack', title: 'Backpack' },
                    { name: 'weapon', title: 'Weapon' },
                    { name: 'armor', title: 'Armor' },
                    { name: 'shield', title: 'Shield' },
                    { name: 'ring', title: 'Ring' },
                    { name: 'legs', title: 'Legs' },
                    { name: 'ammunition', title: 'Ammunition' },
                    { name: 'boots', title: 'Boots' },
                ]
            }
        },

        methods: {
            onSubmit () {
                const form = this.$refs.form

                this.submiting = true
                services.save(this.category.id, new FormData(form))
                    .then(response => {
                        this.submiting = false
                        this.$message.success('Category updated.')
                        this.$router.push({ name: 'admin.categories' })
                    })
                    .catch(() => this.submiting = false)
            }
        },

        mounted () {
            services.find(this.$route.params.id)
                .then(response => {
                    this.category = response.data
                })
        }
    }
</script>