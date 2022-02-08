<template>
    <i class="app-button-animation-spinner app-button-animation-spinner-1"></i>
</template>

<script>
export default {
    name: "button_loading_spinner"
};
</script>

<style>
.app-button-animation-spinner {
    display: block;
    position: relative;
    width: 50%;
    display: grid;
    place-items: center;
}

.app-button-animation-spinner::before,
.app-button-animation-spinner::after {
    content: "";
    box-sizing: border-box;
    position: absolute;
}

.app-button-animation-spinner.app-button-animation-spinner-1::before {
    width: 24px;
    height: 24px;
    border: 4px solid transparent;
    border-top-color: transparent;
    border-radius: 50%;
    animation: loader-1 1s linear infinite;
}

.app-button-animation-spinner.app-button-animation-spinner-1::after {
    width: calc(20px - 2px);
    height: calc(20px - 2px);
    border: 2px solid transparent;
    border-top-color: #00dbff;
    border-radius: 50%;
    animation: appButtonSpinnerLoadingAnimation 0.6s linear reverse infinite;
}

@keyframes appButtonSpinnerLoadingAnimation {
    100% {
        transform: rotate(1turn);
    }
}
</style>
