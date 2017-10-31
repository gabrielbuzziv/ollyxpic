<template>
    <page-load>
        <page-title>
            <img :src="image_path_by_name('item', 'letter')" class="margin-right-10">
            Contact
            <span>Send us a message</span>
        </page-title>

        <panel>
            <p class="margin-bottom-0">
                You can contact me ingame by sending a letter to
                <a href="https://secure.tibia.com/community/?subtopic=characters&name=Lennart the smith" class="text-danger" target="_blank">Lennart the smith</a> on
                <a href="https://secure.tibia.com/community/?subtopic=worlds&world=Harmonia" class="text-danger" target="_blank">Harmonia</a>,
				 This is the best option since i'm more active in Tibia.
            </p>
        </panel>

        <panel>
            <form action="" @submit.prevent="onSubmit" ref="form">
                <div class="row">
                    <form-group label="Name" :columns="6">
                        <form-input name="name" placeholder="Your name" required />
                    </form-group>

                    <form-group label="E-mail" :columns="6">
                        <form-input name="email" placeholder="Your e-mail" required />
                    </form-group>
                </div>

                <form-group label="Subject">
                    <form-input name="subject" placeholder="Subject" required />
                </form-group>

                <form-group label="Message">
                    <textarea name="message" class="form-control" rows="5" required placeholder="What do you have to say?"></textarea>
                </form-group>

                <button class="btn btn-success" type="submit" :disabled="sending">
                    <span v-if="sending">
                        <i class="mdi mdi-loading margin-right-5"></i>
                        Sending ...
                    </span>

                    <span v-else>Send</span>
                </button>
            </form>
        </panel>
    </page-load>
</template>

<script type="text/babel">
    import services from '../services'

    export default {
        data () {
            return {
                sending: false
            }
        },

        methods: {
            onSubmit () {
                const form = this.$refs.form
                this.sending = true

                services.sendContact(new FormData(form))
                        .then(response => {
                            this.$message.success('The e-mail has been sent.')
                            this.sending = false
                        })
                        .catch(error => {
                            this.$message.error('Something went wrong. Check if all fields are filled right.')
                            this.sending = false
                        })
            }
        }
    }
</script>
