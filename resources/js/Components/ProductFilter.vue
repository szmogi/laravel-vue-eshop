<template>
    <div class="flex justify-between py-8 items-center">
        <div class="flex flex-col gap-1">
            <div class="flex flex-row gap-1">
                <div class="flex justify-between items-center">
                    <FloatLabel class="w-full md:w-80">
                        <MultiSelect  @change="filtered()" id="over_label" v-model="categories" :options="useFilter.categories" filter optionLabel="name" class="w-full"  display="chip"  />
                        <label for="over_label">{{ $t('category') }}</label>
                    </FloatLabel>
                </div>
                <div class="flex justify-between items-center">
                    <FloatLabel class="w-full md:w-80">
                        <MultiSelect  @change="filtered()" id="over_label" v-model="colors" :options="useFilter.colors" filter optionLabel="name" class="w-full"   display="chip"  />
                        <label for="over_label">{{ $t('color') }}</label>
                    </FloatLabel>
                </div>
                <div class="flex justify-between items-center">
                    <FloatLabel class="w-full md:w-80">
                        <MultiSelect @change="filtered()" id="over_label" v-model="sizes" :options="useFilter.sizes" filter optionLabel="name" class="w-full"  display="chip"   />
                        <label for="over_label">{{ $t('size') }}</label>
                    </FloatLabel>
                </div>
                <div class="flex ml-4 justify-between items-center">
                    <Button   >
                        {{$t('filterButton')}}
                    </Button>
                    <Button @click="allFilters = true">
                        {{$t('filterAllFilter')}}
                    </Button>
                </div>
            </div>
        </div>
    </div>
    <Drawer v-model:visible="allFilters" header="All filters" position="right" class="w-full md:w-80">
         <div>
             <div class="flex flex-col justify-between items-start ">
                 <h1 class="text-lg pt-4 mb-2 border-b w-full font-bold">{{ $t('category') }}</h1>
                 <div v-for="category of useFilter.categories" :key="category.id" class="flex py-1 items-center">
                     <Checkbox @change="filtered()" v-model="selectedCategories" :inputId="category.name" name="category" :value="category.name" />
                     <label class="ml-2 uppercase w-full" :for="category.name">{{ category.name }}</label>
                 </div>
             </div>
             <div class="flex flex-col justify-between items-start">
                 <h1 class="text-lg pt-4 mb-2 border-b w-full font-bold">{{ $t('color') }}</h1>
                 <div v-for="color of useFilter.colors" :key="color.id" class="flex py-1 items-center">
                     <Checkbox @change="filtered()" v-model="selectedColors" :inputId="color.name" name="color" :value="color.name" />
                     <label class="ml-2 flex flex-row items-center uppercase w-full" :for="color.name">{{ color.name }}
                     <span class="w-4 h-4 border ml-2 block rounded" :style="'background:'+color.name"></span></label>
                 </div>
             </div>
             <div class="flex flex-col justify-between items-start">
                 <h1 class="text-lg pt-4 mb-2 border-b w-full font-bold">{{ $t('size') }}</h1>
                 <div v-for="size of useFilter.sizes" :key="size.id" class="flex py-1 items-center">
                     <Checkbox @change="filtered()" v-model="selectedSizes" :inputId="size.name" name="size" :value="size.name" />
                     <label class="ml-2 uppercase w-full" :for="size.name">{{ size.name }}</label>
                 </div>
             </div>
         </div>
    </Drawer>
</template>


<script setup>
import { useFilterProduct } from "@/stores/useFilterProduct.js";
import MultiSelect from 'primevue/multiselect';
import FloatLabel from 'primevue/floatlabel';
import Drawer from 'primevue/drawer';
import Button from '@/Components/Button.vue';
import Checkbox from 'primevue/checkbox';
import { ref } from 'vue';
const useFilter = useFilterProduct();
useFilter.initFilter();

const categories = ref([]);
const colors = ref([]);
const sizes = ref([]);
const selectedCategories = ref([]);
const selectedColors = ref([]);
const selectedSizes = ref([]);
const allFilters = ref(false);


const filtered = () => {
    let category = '';
    let color = '';
    let size = '';

    Object.keys(categories.value).forEach(key => {
        if(categories.value[key]) {
            category += categories.value[key].id+',';
        }
    });

    Object.keys(colors.value).forEach(key => {
        if(colors.value[key]) {
            color += colors.value[key].id+',';
        }
    });

    Object.keys(sizes.value).forEach(key => {
        if(sizes.value[key]) {
            size += sizes.value[key].id+',';
        }
    });

    useFilter.getFilterContent(category, color, size);
};


</script>
