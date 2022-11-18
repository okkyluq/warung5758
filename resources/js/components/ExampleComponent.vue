<template>
    <div class="alert bg-danger alert-styled-left">
        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span></button>
        <ul>
            <li>
            <span class="text-semibold">Oh snap!</span> Change a few things up and <a href="#" class="alert-link">try submitting agai111111n</a>.
            </li>
            <li>
            <span class="text-semibold">Oh snap!</span> Change a few things up and <a href="#" class="alert-link">try submitting again</a>.
            </li>

        </ul>
    </div>
</template>

<script>
    export default {
        mounted() { 
            console.log('Component mounted.')
        }
    }
</script>
