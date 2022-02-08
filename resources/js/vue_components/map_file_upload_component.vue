<template>
    <div>

        <div class="progress">
            <div class="progress-bar" role="progressbar" :style="{width: progressPhotoN + '%'}">
                {{ progressPhotoName }}
            </div>
        </div>

        <input type="file" name="bGetFiles" multiple="" @change="photoInputChange()">

    </div>
</template>

<script>
    export default {
        data() {
            return {
                currentPlacemarkMapID: 0, // обьект карты, к которому Пользователь хочет прикрепть фото
                progressPhotoN: 0,
                progressPhotoName: '',
            }
        },
        mounted() {
        },
        methods: {

            // клик на выбрать файл
            async photoInputChange() {
                // записть в переменную Vue, чтобы избежать ситуации, что Пользователь уже нажал на др.обьект
                this.currentPlacemarkMapID = mmCurrentPlacemarkMapID;
                // список фото, указанные через обзор
                let photos = Array.from(event.target.files);
                // очистить массив фото
                if (mmObjects[this.currentPlacemarkMapID]['photos'] == null) {
                    mmObjects[this.currentPlacemarkMapID]['photos'] = [];
                }
                for (let item of photos) {
                    // загрузка файлов на хостинг
                    await this.uploadPhoto(item);
                }
                // показать детали выбранной точки
                funRBviewPlacemark(mmObjects[this.currentPlacemarkMapID]);
            },

            // загрузка файлов на хостинг
            async uploadPhoto(item) {

                // данные post-запроса
                let form = new FormData();
                form.append('image', item);
                form.append('getModelDir', 'map');
                form.append('getModelId', 'photos');

                await axios
                    .post('/api/uploadModelFiles', form, {
                            onUploadProgress: (itemUpload) => {
                                this.progressPhotoN = Math.round((itemUpload.loaded / itemUpload.total) * 100);
                                this.progressPhotoName = item.name + ' ' + this.progressPhotoN;
                            }
                        }
                    )
                    .then(response => {
                        // сгенерированнео имя файла с путем
                        let newFullName = response.data;
                        // оставить только сгениртрованное имя без пути
                        newFullName = newFullName.replace('uploads/models/map/photos/', '');
                        //console.log(newFullName);
                        // записать в массив присвоенное имя фото
                        mmObjects[this.currentPlacemarkMapID]['photos'].push(newFullName);

                        this.progressPhotoN = 0;
                        this.progressPhotoName = '';
                    })
                    .catch(error => {
                        console.log(error);
                    })
            },
        }
    }
</script>
