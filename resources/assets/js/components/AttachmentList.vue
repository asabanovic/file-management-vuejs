<template>
    <div class="file-upload">
        <div class="form-group">
            <label for="logo" class="control-label">Listing <span class="label label-primary">Total Size: {{ (Number(upload_size / 1024 /1024)).toFixed(2) }}MB</span></label>
            <br><br>
            <div class="form-group">
                <div class="form-group files">
                    <div class="attachment-holder animated fadeIn" v-cloak v-for="(attachment, index) in attachments"> 
                        <div class="form-group">
                            <span class="label label-primary"><a target="_blank" class="link" :href="attachment.path">{{ attachment.name + ' (' + Number((attachment.size / 1024 / 1024).toFixed(1)) + 'MB)'}}</a></span> 
                            <span class="label label-success" >{{ attachment.category.name }}</span>
                            <span class="label label-warning" >{{ attachment.time }}</span>
                            <span class="" style="background: red; cursor: pointer;" @click="removeAttachment(attachment)"><button class="btn btn-xs btn-danger">Remove</button></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {

    props: [
    'settings'
    ],

    data() {

        return {

                // You can store all your files here
                attachments: [],

                upload_size: 0
            }

        },

        watch: {

        },

        computed: {


        },

        methods: {

            removeServerAttachment(attachment_id){

                window.Event.fire('loading_on');
                let data = {
                    params: 
                    {
                        attachment_id: attachment_id
                    }
                };

                // Make HTTP request to store announcement
                axios.delete(this.settings.file_management.delete_attachment, data)
                .then(function (response) {
                    console.log(response);
                    if (response.data.success) {
                        toastr.success('File deleted!', 'Success');
                        this.getAttachmentSize();
                    } else {
                        console.log(response.data.errors);
                        toastr.error('Something went wront', 'Error');
                    }
                    window.Event.fire('loading_off');
                }.bind(this)) // Make sure we bind Vue Component object to this funtion so we get a handle of it in order to call its other methods
                .catch(function (error) {
                    console.log(error);
                    window.Event.fire('loading_off');
                });
                
            },

            pullAttachments() {

                window.Event.fire('loading_on');

                // Make HTTP request to store announcement
                axios.post(this.settings.file_management.pull_attachments)
                .then(function (response) {
                    console.log(response);
                    if (response.data.success) {
                        this.attachments = response.data.data;
                        console.log('Attachments: ', this.attachments);
                        toastr.success('We just pulled all the attachments', 'Background Task: Success');
                        this.getAttachmentSize();
                    } else {
                        console.log(response.data.errors);
                        toastr.warning('Cannot pull attachments. User has to be logged in', 'Background Task: Warning');
                    }
                    window.Event.fire('loading_off');
                }.bind(this)) // Make sure we bind Vue Component object to this funtion so we get a handle of it in order to call its other methods
                .catch(function (error) {
                    console.log(error);
                    window.Event.fire('loading_off');
                });

            },

            getAttachmentSize() {

                this.upload_size = 0; // Reset to beginningƒ

                this.attachments.map((item) => { this.upload_size += parseInt(item.size); });
                
                this.upload_size = Number((this.upload_size).toFixed(1));

                this.$forceUpdate();

            },

            removeAttachment(attachment) {

                this.removeServerAttachment(attachment.id);

                this.attachments.splice(this.attachments.indexOf(attachment), 1);
                
                this.getAttachmentSize();

            },

            start() {
                console.log('Starting Attachment List Component');
                this.pullAttachments();
            },

        },

        created() {
            this.start();

            window.Event.listen('reload_files', function() {
                console.log('Received Reload Files Event!');
                this.pullAttachments(); // Pull attachments again
            }.bind(this));
        }

    }
    </script>