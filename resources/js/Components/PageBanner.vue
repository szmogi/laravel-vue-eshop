<template>
    <div class="banner-slider min-w-full">
        <swiper
            :slides-per-view="1"
            :space-between="30"
            :loop="true"
            :autoplay="true"
            :pagination="{ clickable: true }"
            :navigation="true"
            :modules="modules"
        >
            <swiper-slide v-for="(slide, index) in slides" :key="index">
                <img class="h-[800px] w-full object-cover" :src="slide.image" alt="" />
                <div class="slide-content top-[40%] ">
                    <h2 class="text-3xl mb-6 font-bold tracking-tight text-white sm:text-6xl">{{ $t('bannerTitle') }}</h2>
                    <h2 class="text-2xl mb-2 font-bold tracking-tight text-white sm:text-3xl">{{ $t('bannerDescription') }}</h2>

                </div>
            </swiper-slide>

            <!-- Optional Pagination -->
            <div class="swiper-pagination" slot="pagination"></div>
        </swiper>
    </div>
</template>

<script>
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Navigation, Pagination , Autoplay} from 'swiper/modules';
import 'swiper/css/pagination';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/autoplay'


export default {
    components: {
        Swiper,
        SwiperSlide
    },
    data() {
        return {
            slides: [
                {
                    image: '/paper-bags-different-colors-blue-background-top-view-min.jpg',
                },
                {
                    image: '/paper-bags-different-colors-blue-background-top-view-min.jpg',
                },
                {
                    image: '/paper-bags-different-colors-blue-background-top-view-min.jpg',
                }
            ],
            modules: [Navigation, Pagination , Autoplay],
        };
    },
    mounted() {
    },
}
</script>

<style scoped>
.banner-slider {
    width: 100%;
    max-width: 800px;
    margin: 0 auto;
}

.swiper-slide img {
    width: 100%;
    height: 600px;
    object-fit: cover;
}

.slide-content {
    position: absolute;
    bottom: 20px;
    left: 50px;
    color: white;
}

.swiper-pagination {
    position: absolute;
    bottom: 10px;
    right: 10px;
}
</style>
