<template>
    <div class="container login">
        <div class="bubbles-container">
            <svg class="bubbles" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 701 1024" style="overflow: visible;">

                <g class="bubbles-large" stroke-width="7">
                    <g>
                        <g transform="translate(10 940)">
                            <circle cx="35" cy="35" r="35"/>
                        </g>
                    </g>
                    <g>
                        <g transform="translate(373 940)">
                            <circle cx="35" cy="35" r="35"/>
                        </g>
                    </g>
                    <g>
                        <g transform="translate(408 940)">
                            <circle cx="35" cy="35" r="35"/>
                        </g>
                    </g>
                    <g>
                        <g transform="translate(621 940)">
                            <circle cx="35" cy="35" r="35"/>
                        </g>
                    </g>
                    <g>
                        <g transform="translate(179 940)">
                            <circle cx="35" cy="35" r="35"/>
                        </g>
                    </g>
                </g>

                <g class="bubbles-small" stroke-width="4">
                    <g>
                        <g transform="translate(147 984)">
                            <circle cx="15" cy="15" r="15"/>
                        </g>
                    </g>
                    <g>
                        <g transform="translate(255 984)">
                            <circle cx="15" cy="15" r="15"/>
                        </g>
                    </g>
                    <g>
                        <g transform="translate(573 984)">
                            <circle cx="15" cy="15" r="15"/>
                        </g>
                    </g>
                    <g>
                        <g transform="translate(429 984)">
                            <circle cx="15" cy="15" r="15"/>
                        </g>
                    </g>
                    <g>
                        <g transform="translate(91 984)">
                            <circle cx="15" cy="15" r="15"/>
                        </g>
                    </g>
                    <g>
                        <g transform="translate(640 984)">
                            <circle cx="15" cy="15" r="15"/>
                        </g>
                    </g>
                    <g>
                        <g transform="translate(321 984)">
                            <circle cx="15" cy="15" r="15"/>
                        </g>
                    </g>
                    <g>
                        <g transform="translate(376 984)">
                            <circle cx="15" cy="15" r="15"/>
                        </g>
                    </g>
                    <g>
                        <g transform="translate(376 984)">
                            <circle cx="15" cy="15" r="15"/>
                        </g>
                    </g>
                    <g>
                        <g transform="translate(497 984)">
                            <circle cx="15" cy="15" r="15"/>
                        </g>
                    </g>
                </g>

            </svg>
        </div>

        <div class="row justify-content-center align-items-center" style="height:70vh">
            <div class="col-4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title text-center mb-4"><i class="fas fa-envelope"></i> Betterprotect</h3>
                        <form autocomplete="off" @submit.stop.prevent="login" method="post">
                            <div class="form-group">
                                <label for="username"><i class="fas fa-user"></i> Benutzer</label>
                                <input :class="{ 'is-invalid': error }" type="text" id="username" class="form-control" v-model="username" autofocus @click="clearError">

                                <b-form-invalid-feedback>
                                    {{ this.error }}
                                </b-form-invalid-feedback>
                            </div>

                            <div class="form-group">
                                <label for="password"><i class="fas fa-key"></i> Passwort</label>
                                <input type="password" id="password" class="form-control" v-model="password">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                username: null,
                password: null,
                error: false
            }
        },

        methods: {
            clearError() {
                this.error = false;
            },
            login(){
                this.$auth.login({
                    data: {
                        username: this.username,
                        password: this.password
                    },
                    success: function () {
                        this.clearError();
                    },
                    error: (error) => {
                        this.error = error.response.data.message;
                    },
                    rememberMe: true,
                    redirect: '/',
                    fetchUser: true,
                });
            },
        }
    }
</script>

<style>
    h1 {
        font-family: 'Exo 2', sans-serif;
        font-size: 8.5vw;
        color: white;
        padding: 5rem 0;
        text-shadow: 0 4px 48px rgba(255, 255, 255, 0.2);
    }

    .bubbles-container {
        position: absolute;
        top: 0;
        left: 50%;
        width: 100%;
        max-width: 15rem;
        -webkit-transform: translateX(-50%);
        transform: translateX(-50%);
        opacity: 0.75;
        overflow: visible;
    }

    .bubbles {
        width: 100%;
        height: auto;
    }
    .bubbles circle {
        stroke: white;
        fill: none;
    }
    .bubbles > g > g:nth-of-type(3n) circle {
        stroke: #87f5fb;
    }
    .bubbles > g > g:nth-of-type(4n) circle {
        stroke: #8be8cb;
    }

    .bubbles-large {
        overflow: visible;
    }
    .bubbles-large > g {
        -webkit-transform: translateY(2048px);
        transform: translateY(2048px);
        opacity: 0;
        will-change: transform, opacity;
    }
    .bubbles-large g:nth-of-type(1) {
        -webkit-animation: up 6.5s infinite;
        animation: up 6.5s infinite;
    }
    .bubbles-large g:nth-of-type(1) g {
        -webkit-transform: translateX(350px);
        transform: translateX(350px);
    }
    .bubbles-large g:nth-of-type(1) circle {
        -webkit-animation: wobble 3s infinite ease-in-out;
        animation: wobble 3s infinite ease-in-out;
    }
    .bubbles-large g:nth-of-type(2) {
        -webkit-animation: up 5.25s 250ms infinite;
        animation: up 5.25s 250ms infinite;
    }
    .bubbles-large g:nth-of-type(2) g {
        -webkit-transform: translateX(450px);
        transform: translateX(450px);
    }
    .bubbles-large g:nth-of-type(2) circle {
        -webkit-animation: wobble 3s infinite ease-in-out;
        animation: wobble 3s infinite ease-in-out;
    }
    .bubbles-large g:nth-of-type(3) {
        -webkit-animation: up 6s 750ms infinite;
        animation: up 6s 750ms infinite;
    }
    .bubbles-large g:nth-of-type(3) g {
        -webkit-transform: translateX(700px);
        transform: translateX(700px);
    }
    .bubbles-large g:nth-of-type(3) circle {
        -webkit-animation: wobble 3s infinite ease-in-out;
        animation: wobble 3s infinite ease-in-out;
    }
    .bubbles-large g:nth-of-type(4) {
        -webkit-animation: up 5.5s 1.5s infinite;
        animation: up 5.5s 1.5s infinite;
    }
    .bubbles-large g:nth-of-type(4) g {
        -webkit-transform: translateX(500px);
        transform: translateX(500px);
    }
    .bubbles-large g:nth-of-type(4) circle {
        -webkit-animation: wobble 3s infinite ease-in-out;
        animation: wobble 3s infinite ease-in-out;
    }
    .bubbles-large g:nth-of-type(5) {
        -webkit-animation: up 6.5s 4s infinite;
        animation: up 6.5s 4s infinite;
    }
    .bubbles-large g:nth-of-type(5) g {
        -webkit-transform: translateX(675px);
        transform: translateX(675px);
    }
    .bubbles-large g:nth-of-type(5) circle {
        -webkit-animation: wobble 3s infinite ease-in-out;
        animation: wobble 3s infinite ease-in-out;
    }

    .bubbles-small {
        overflow: visible;
    }
    .bubbles-small > g {
        -webkit-transform: translateY(2048px);
        transform: translateY(2048px);
        opacity: 0;
        will-change: transform, opacity;
    }
    .bubbles-small g circle {
        -webkit-transform: scale(0);
        transform: scale(0);
    }
    .bubbles-small g:nth-of-type(1) {
        -webkit-animation: up 5.25s infinite;
        animation: up 5.25s infinite;
    }
    .bubbles-small g:nth-of-type(1) g {
        -webkit-transform: translateX(350px);
        transform: translateX(350px);
    }
    .bubbles-small g:nth-of-type(1) circle {
        -webkit-animation: wobble 3s infinite ease-in-out;
        animation: wobble 3s infinite ease-in-out;
    }
    .bubbles-small g:nth-of-type(2) {
        -webkit-animation: up 5.75s infinite;
        animation: up 5.75s infinite;
    }
    .bubbles-small g:nth-of-type(2) g {
        -webkit-transform: translateX(750px);
        transform: translateX(750px);
    }
    .bubbles-small g:nth-of-type(2) circle {
        -webkit-animation: wobble 3s infinite ease-in-out;
        animation: wobble 3s infinite ease-in-out;
    }
    .bubbles-small g:nth-of-type(3) {
        -webkit-animation: up 5.25s 250ms infinite;
        animation: up 5.25s 250ms infinite;
    }
    .bubbles-small g:nth-of-type(3) g {
        -webkit-transform: translateX(350px);
        transform: translateX(350px);
    }
    .bubbles-small g:nth-of-type(3) circle {
        -webkit-animation: wobble 3s 250ms infinite ease-in-out;
        animation: wobble 3s 250ms infinite ease-in-out;
    }
    .bubbles-small g:nth-of-type(4) {
        -webkit-animation: up 5.75s 325ms infinite;
        animation: up 5.75s 325ms infinite;
    }
    .bubbles-small g:nth-of-type(4) g {
        -webkit-transform: translateX(180px);
        transform: translateX(180px);
    }
    .bubbles-small g:nth-of-type(4) circle {
        -webkit-animation: wobble 3s 325ms infinite ease-in-out;
        animation: wobble 3s 325ms infinite ease-in-out;
    }
    .bubbles-small g:nth-of-type(5) {
        -webkit-animation: up 6s 125ms infinite;
        animation: up 6s 125ms infinite;
    }
    .bubbles-small g:nth-of-type(5) g {
        -webkit-transform: translateX(350px);
        transform: translateX(350px);
    }
    .bubbles-small g:nth-of-type(5) circle {
        -webkit-animation: wobble 3s 250ms infinite ease-in-out;
        animation: wobble 3s 250ms infinite ease-in-out;
    }
    .bubbles-small g:nth-of-type(6) {
        -webkit-animation: up 5.13s 250ms infinite;
        animation: up 5.13s 250ms infinite;
    }
    .bubbles-small g:nth-of-type(6) g {
        -webkit-transform: translateX(650px);
        transform: translateX(650px);
    }
    .bubbles-small g:nth-of-type(6) circle {
        -webkit-animation: wobble 3s 125ms infinite ease-in-out;
        animation: wobble 3s 125ms infinite ease-in-out;
    }
    .bubbles-small g:nth-of-type(7) {
        -webkit-animation: up 6.25s 350ms infinite;
        animation: up 6.25s 350ms infinite;
    }
    .bubbles-small g:nth-of-type(7) g {
        -webkit-transform: translateX(480px);
        transform: translateX(480px);
    }
    .bubbles-small g:nth-of-type(7) circle {
        -webkit-animation: wobble 3s 325ms infinite ease-in-out;
        animation: wobble 3s 325ms infinite ease-in-out;
    }
    .bubbles-small g:nth-of-type(8) {
        -webkit-animation: up 7s 200ms infinite;
        animation: up 7s 200ms infinite;
    }
    .bubbles-small g:nth-of-type(8) g {
        -webkit-transform: translateX(330px);
        transform: translateX(330px);
    }
    .bubbles-small g:nth-of-type(8) circle {
        -webkit-animation: wobble 3s 325ms infinite ease-in-out;
        animation: wobble 3s 325ms infinite ease-in-out;
    }
    .bubbles-small g:nth-of-type(9) {
        -webkit-animation: up 6.25s 233ms infinite;
        animation: up 6.25s 233ms infinite;
    }
    .bubbles-small g:nth-of-type(9) g {
        -webkit-transform: translateX(230px);
        transform: translateX(230px);
    }
    .bubbles-small g:nth-of-type(9) circle {
        -webkit-animation: wobble 3s 275ms infinite ease-in-out;
        animation: wobble 3s 275ms infinite ease-in-out;
    }
    .bubbles-small g:nth-of-type(10) {
        -webkit-animation: up 6s 900ms infinite;
        animation: up 6s 900ms infinite;
    }
    .bubbles-small g:nth-of-type(10) g {
        -webkit-transform: translateX(730px);
        transform: translateX(730px);
    }
    .bubbles-small g:nth-of-type(10) circle {
        -webkit-animation: wobble 2s 905ms infinite ease-in-out;
        animation: wobble 2s 905ms infinite ease-in-out;
    }

    @-webkit-keyframes wobble {
        33% {
            -webkit-transform: translateX(-50px);
            transform: translateX(-50px);
        }
        66% {
            -webkit-transform: translateX(50px);
            transform: translateX(50px);
        }
    }

    @keyframes wobble {
        33% {
            -webkit-transform: translateX(-50px);
            transform: translateX(-50px);
        }
        66% {
            -webkit-transform: translateX(50px);
            transform: translateX(50px);
        }
    }
    @-webkit-keyframes up {
        0% {
            opacity: 0;
        }
        10%, 90% {
            opacity: 1;
        }
        100% {
            opacity: 0;
            -webkit-transform: translateY(-1024px);
            transform: translateY(-1024px);
        }
    }
    @keyframes up {
        0% {
            opacity: 0;
        }
        10%, 90% {
            opacity: 1;
        }
        100% {
            opacity: 0;
            -webkit-transform: translateY(-1024px);
            transform: translateY(-1024px);
        }
    }

</style>
