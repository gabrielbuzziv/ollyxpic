<template>
    <div :class="panelClass">
        <div class="panel-heading" v-if="showHeading">
            <slot name="heading">
                <div class="pull-left">
                    <h3 class="panel-title">
                        <i :class="iconClass" v-if="icon"></i>
                        {{ title }}
                    </h3>
                </div>

                <div class="pull-right" v-if="toggleable">
                    <a href="" @click.prevent="isOpen = false" v-if="isOpen">
                        <i class="mdi mdi-chevron-up"></i>
                    </a>

                    <a href="" @click.prevent="isOpen = true" v-else>
                        <i class="mdi mdi-chevron-down"></i>
                    </a>
                </div>

                <div class="clearfix"></div>
            </slot>
        </div>

        <transition name="fade">
            <div class="panel-body" v-if="$slots.default && isOpen">
                <slot></slot>
            </div>
        </transition>

        <div class="panel-footer" v-if="$slots.footer">
            <slot name="footer"></slot>
        </div>
    </div>
</template>

<script type="text/babel">
    export default {
        props: {
            type: {
                type: String,
                default: 'default'
            },

            title: {
                type: String,
                default: null
            },

            icon: {
                type: String,
                default: null
            },

            toggleable: {
                type: Boolean,
                default: false
            },

            open: {
                type: Boolean,
                default: true
            }
        },

        data () {
            return {
                isOpen: true
            }
        },

        watch: {
            open () {
                this.isOpen = this.open
            }
        },

        computed: {
            panelClass () {
                return ['panel', `panel-${this.type}`]
            },

            iconClass () {
                return ['mdi', `mdi-${this.icon}`, 'margin-right-5']
            },

            showHeading () {
                return this.title != null || this.$slots.heading
            }
        },

        mounted () {
            this.isOpen = this.open
        }
    }
</script>
