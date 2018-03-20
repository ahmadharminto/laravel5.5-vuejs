<template>
    <div>
        <li class="list-group-item" :class="className">
            <slot></slot>
            <span id="time">{{ time }}</span>
            <br>
            <small class="badge float-right" :class="badgeClass">{{ user }}</small>
        </li>
        <br>
    </div>
</template>

<style>
    #time {
        font-size: 9px;
        margin-top: 10px;
        color: black
    }
</style>


<script>    
    export default {
        props: [
            'color',
            'user',
            'time'
        ],
        computed: {
            className() {
                return 'list-group-item-' + this.color;
            },
            badgeClass() {
                return 'badge-' + this.color;
            }
        },
        data(){
            return {}
        },
        mounted()
        {
            
        },
        methods: {
            
        }
    }
</script>
