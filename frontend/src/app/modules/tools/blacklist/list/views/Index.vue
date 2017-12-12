<template>
    <page-load id="blacklist">
        <page-title>
            <img :src="image_path_by_name('item', 'Book (Black)')" class="margin-right-5">
            <div class="title">
                <h2>Blacklist</h2>
                <span>Quick Loot</span>
            </div>
        </page-title>

        <page-load class="no-padding" :loading="loading">
            <panel title="How to update the blacklist?" :open="false" toggleable>
                <ol>
                    <li>Login on the character you want to update</li>
                    <li>Add any item to the blacklist</li>
                    <li>Logout and close the Tibia client</li>
                    <li>
                        <code>Windows + R</code>
                        <i class="mdi mdi-arrow-right"></i>
                        <input type="text" class="form-control" value="%LOCALAPPDATA%\Tibia\packages\Tibia" style="display: inline-block; width: 320px;">
                    </li>
                    <li>Open <code>characterdata</code> folder <small>(Sor by data, most recent first)</small></li>
                    <li>Open the top folder</li>
                    <li>Edit the file <code>lootBlackWhitelist.json</code> in any editor</li>
                    <li>Paste the generated blacklist.</li>
                    <li>Save and close the file</li>
                    <li>Done</li>
                </ol>

                <small>Source:
                    <a href="https://www.reddit.com/r/TibiaMMO/comments/7honca/dec_05_2017_winter_update_2017/">Reddit</a>
                    by
                    <a href="https://www.reddit.com/user/TheSwedeIrishman">TheSwedenIrishman</a>
                </small>
            </panel>

            <panel>
                <el-select v-model="category"
                    no-data-text="Categories not found"
                    no-match-text="Category not found"
                    placeholder="Categories"
                    filterable>
                    <el-option v-for="category in categories" :value="category.id" :label="category.title" :key="category.id"></el-option>
                </el-select>

                <div class="pull-right">
                    <!--<el-tooltip content="If you already have your blacklist, paste here and edit in real time." placement="left">-->
                        <!--<button class="btn">-->
                            <!--<i class="mdi mdi-pencil margin-right-5"></i>-->
                            <!--Load-->
                        <!--</button>-->
                    <!--</el-tooltip>-->

                    <button class="btn btn-success" @click.prevent="showBlacklist">
                        <i class="mdi mdi-check margin-right-5"></i>
                        Generate
                    </button>
                </div>

                <div class="clearfix"></div>
            </panel>

            <panel class="items">
                <div class="col-md-2" v-for="item in items">
                    <div class="item" :class="{ 'active': isSelected(item.identifier) }" @click.prevent="toggleItem(item.identifier)">
                        <div class="thumb">
                            <img :src="image_path('item', item.id)">
                        </div>
                        <span class="name">{{ item.title }}</span>
                    </div>
                </div>
            </panel>

            <small>
                Credit for <a href="https://www.reddit.com/user/TheSwedeIrishman">TheSwedenIrishman</a>
                for providing the id of items.
            </small>
        </page-load>

        <blacklist :blacklist="blacklist" />
    </page-load>
</template>

<script>
    import Blacklist from './Blacklist'
    import services from '../services'

    export default {
        components: { Blacklist },

        data () {
            return {
                loading: true,
                itemsList: [],
                categories: [],
                category: 1,
                blacklist: {
                    "blacklistTypes": [],
                    "listType": "blacklist",
                    "whitelistTypes": []
                },
                blacklistVisible: false
            }
        },

        computed: {
            items () {
                return typeof this.category == 'number'
                    ? this.itemsList.filter(item => item.category_id == this.category)
                    : this.itemsList
            }
        },

        methods: {
            isSelected (identifier) {
                return this.blacklist.blacklistTypes.indexOf(identifier) != -1
            },

            toggleItem (identifier) {
                if (this.blacklist.blacklistTypes.indexOf(identifier) != -1) {
                    const index = this.blacklist.blacklistTypes.indexOf(identifier)
                    this.blacklist.blacklistTypes.splice(index, 1)
                } else {
                    this.blacklist.blacklistTypes.push(identifier)
                }
            },

            showBlacklist () {
                this.$root.$emit('generate::blacklist')
            }
        },

        mounted () {
            services.getCategories()
                .then(response => {
                    this.categories = response.data

                    services.getItems()
                        .then(response => {
                            this.itemsList = response.data
                            this.loading = false
                        })
                        .catch(() => this.loading = false)
                })
                .catch(() => this.loading = false)
        }
    }
</script>