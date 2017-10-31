<template>
    <form action="" method="POST" class="imbuements-form" @submit.prevent="onSubmit" ref="form">
        <input type="hidden" name="_method" :value="method">

        <div class="row">
            <div class="form-group col-md-6">
                <label>Title</label>
                <input type="text" name="title" class="form-control" v-model="data.title">
            </div>

            <div class="form-group col-md-6">
                <label>Name</label>
                <input type="text" name="name" class="form-control" v-model="data.name">
            </div>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="5" v-model="data.description"></textarea>
        </div>

        <div class="row items">
            <div class="col-md-4" v-for="imbuement, index in data.items">
                <div class="item">
                    <h4>{{ getImbuementTierName(imbuement.tier) }}</h4>

                    <div class="form-group">
                        <el-select class="block" v-model="data.items[index].item_id" placeholder="Select the item" filterable clearable>
                            <el-option v-for="item in items" :value="item.id" :label="item.title" :key="item.id">
                                <img :src="image_path('item', item.id)" class="margin-right-5" height="25">
                                {{ item.title }}
                            </el-option>
                        </el-select>

                        <input type="hidden" :name="`items[${index}]['id']`" :value="data.items[index].item_id">
                    </div>

                    <div class="form-group">
                        <input type="text"
                               :name="`items[${index}]['amount']`"
                               class="form-control"
                               placeholder="Amount"
                               v-model="data.items[index].amount">
                    </div>
                </div>
            </div>
        </div>

        <div class="margin-top-40">
            <button type="submit" class="btn btn-success" :disabled="submiting">
                <i class="mdi mdi-check margin-right-5"></i>
                Save <span v-if="submiting">...</span>
            </button>

            <router-link :to="{ name: 'admin.news.list' }" class="btn btn-blank">
                Cancel
            </router-link>
        </div>
    </form>
</template>

<script>
    import services from '../services'

    export default {
        props: ['action', 'method', 'data'],

        data () {
            return {
                submiting: false,
                items: []
            }
        },

        methods: {
            onSubmit () {
                const data = {
                    _method: this.method,
                    ...this.data
                }

                this.submiting = true
                services.save(this.action, data)
                    .then(response => {
                        this.submiting = false

                        if (response.status == 201) {
                            this.$message.success('The imbuement has been added.')
                        } else if (response.status == 200) {
                            this.$message.success('The imbuement has been updated.')
                        }
                        this.$router.push({ name: 'admin.imbuements' })
                    })
                    .catch(() => {
                        this.validation()
                        this.submiting = false
                    })
            },

            loadItems () {
                services.getItems()
                    .then(response => this.items = response.data)
            },

            getImbuementTierName (tier) {
                switch (tier) {
                    case 1:
                        return 'Basic'
                    case 2:
                        return 'Intricate'
                    case 3:
                        return 'Powerful'
                }
            }
        },

        mounted () {
            this.loadItems()
        }
    }
</script>