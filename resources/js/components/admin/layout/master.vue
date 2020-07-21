<template>
    <div>
        <headervue></headervue>
        <div class="d-flex align-items-stretch">
            <menuvue></menuvue>
            <pagecontent></pagecontent>
        </div>
    </div>
</template>

<script>
    import headervue from './header.vue';
    import pagecontent from './PageContent.vue';
    import menuvue from './menu.vue';

    export default {

        components: {
            headervue       : headervue,
            pagecontent     : pagecontent,
            menuvue         : menuvue,
        }
    }
</script>
