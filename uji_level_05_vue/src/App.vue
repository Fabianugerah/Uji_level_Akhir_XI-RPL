<script setup>
import Footer from './components/TheFooter.vue'
import BottomNav from './components/BottomNav.vue'



// Flowbite JS
import { onMounted } from 'vue'
import { Modal } from 'flowbite'

onMounted(() => {
    const $buttonElement = document.querySelector('#button');
    const $modalElement = document.querySelector('#modal');
    const $closeButton = document.querySelector('#closeButton');

    const modalOptions = {
        backdropClasses: 'bg-gray-900/50 dark:bg-gray-900/80 fixed inset-0 z-40'
    }

    if ($modalElement) {
        const modal = new Modal($modalElement, modalOptions);
        $buttonElement.addEventListener('click', () => modal.toggle());
        $closeButton.addEventListener('click', () => modal.hide());

        // programmatically show
        // modal.show();
    }
})
</script>


<template>
  <div>
    <RouterView/>
    <Footer></Footer>
    <BottomNav></BottomNav>
  </div>
</template>

