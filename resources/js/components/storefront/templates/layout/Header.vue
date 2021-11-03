<template>
    <header id="header" class="header fixed-top d-flex align-items-center">
        <div class="d-flex align-items-center justify-content-between">
            <router-link to="/" class="logo d-flex align-items-center text-decoration-none">
                <img src="/assets/img/logo.png" alt="">
                <span class="d-none d-lg-block">MCFeede</span>
            </router-link>
            <button class="btn btn-outline-primary toggle-sidebar-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
                </svg>
            </button>
        </div><!-- End Logo -->

        <div class="search-bar">
            <form class="search-form d-flex align-items-center">
                <input type="text" name="query" placeholder="Search" title="Enter search keyword">
                <button type="submit" title="Search">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                    </svg>
                </button>
            </form>
        </div><!-- End Search Bar -->
        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">
                <li class="nav-item d-block d-lg-none">
                    <a class="nav-link nav-icon search-bar-toggle " href="#"><i class="bi bi-search"></i></a>
                </li><!-- End Search Icon-->

                <li class="nav-item dropdown pe-3">

                    <a v-if="loadedProfile" class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                        <!-- <img src="/assets/img/profile-img.jpg" alt="Profile" class="rounded-circle"> -->
                        <div class="nav-profile__avatar rounded-circle d-flex justify-content-center align-items-center bg-primary fw-bold text-white">{{ firstCharacter }}</div>
                        <span class="d-none d-md-block dropdown-toggle ps-2">{{ firstName }}</span>
                    </a>
                    <a v-else class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                        <div class="nav-profile__avatar rounded-circle d-flex justify-content-center align-items-center bg-primary fw-bold text-white">&nbsp;</div>
                        <span class="d-none d-md-block dropdown-toggle ps-2">____</span>
                    </a>
                    

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li class="dropdown-header">
                            <h6>{{ profile.name }}</h6>
                            <span>{{ profile.store_url }}</span>
                        </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <router-link to="/store/profile" class="dropdown-item d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person me-2" viewBox="0 0 16 16">
                                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                            </svg>
                            <span>My Profile</span>
                        </router-link>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="https://deepplus.plus/contact-us/form" target="_blank">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-patch-question me-2" viewBox="0 0 16 16">
                                <path d="M8.05 9.6c.336 0 .504-.24.554-.627.04-.534.198-.815.847-1.26.673-.475 1.049-1.09 1.049-1.986 0-1.325-.92-2.227-2.262-2.227-1.02 0-1.792.492-2.1 1.29A1.71 1.71 0 0 0 6 5.48c0 .393.203.64.545.64.272 0 .455-.147.564-.51.158-.592.525-.915 1.074-.915.61 0 1.03.446 1.03 1.084 0 .563-.208.885-.822 1.325-.619.433-.926.914-.926 1.64v.111c0 .428.208.745.585.745z"/>
                                <path d="m10.273 2.513-.921-.944.715-.698.622.637.89-.011a2.89 2.89 0 0 1 2.924 2.924l-.01.89.636.622a2.89 2.89 0 0 1 0 4.134l-.637.622.011.89a2.89 2.89 0 0 1-2.924 2.924l-.89-.01-.622.636a2.89 2.89 0 0 1-4.134 0l-.622-.637-.89.011a2.89 2.89 0 0 1-2.924-2.924l.01-.89-.636-.622a2.89 2.89 0 0 1 0-4.134l.637-.622-.011-.89a2.89 2.89 0 0 1 2.924-2.924l.89.01.622-.636a2.89 2.89 0 0 1 4.134 0l-.715.698a1.89 1.89 0 0 0-2.704 0l-.92.944-1.32-.016a1.89 1.89 0 0 0-1.911 1.912l.016 1.318-.944.921a1.89 1.89 0 0 0 0 2.704l.944.92-.016 1.32a1.89 1.89 0 0 0 1.912 1.911l1.318-.016.921.944a1.89 1.89 0 0 0 2.704 0l.92-.944 1.32.016a1.89 1.89 0 0 0 1.911-1.912l-.016-1.318.944-.921a1.89 1.89 0 0 0 0-2.704l-.944-.92.016-1.32a1.89 1.89 0 0 0-1.912-1.911l-1.318.016z"/>
                                <path d="M7.001 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0z"/>
                            </svg>
                            <span>Need Help?</span>
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <router-link to="/logout" class="dropdown-item d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-forward me-2" viewBox="0 0 16 16">
                                <path d="M9.502 5.513a.144.144 0 0 0-.202.134V6.65a.5.5 0 0 1-.5.5H2.5v2.9h6.3a.5.5 0 0 1 .5.5v1.003c0 .108.11.176.202.134l3.984-2.933a.51.51 0 0 1 .042-.028.147.147 0 0 0 0-.252.51.51 0 0 1-.042-.028L9.502 5.513zM8.3 5.647a1.144 1.144 0 0 1 1.767-.96l3.994 2.94a1.147 1.147 0 0 1 0 1.946l-3.994 2.94a1.144 1.144 0 0 1-1.767-.96v-.503H2a.5.5 0 0 1-.5-.5v-3.9a.5.5 0 0 1 .5-.5h6.3v-.503z"/>
                            </svg>
                            <span>Sign Out</span>
                        </router-link>
                    </li>
                    </ul><!-- End Profile Dropdown Items -->
                </li><!-- End Profile Nav -->

            </ul>
        </nav>
    </header>
</template>


<script>
import Dropdown from 'bootstrap/js/dist/dropdown';
import { mapGetters, mapState } from 'vuex';
export default {
    data: () => ({
        loadedProfile: false
    }),
    created() {
        if(this.isUserToken) {
            this.$store.dispatch('getUserProfile').then(() => {
                this.loadedProfile = true
            })
        }
    },
    mounted() {
        let dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'))
        if(dropdownElementList) {
            dropdownElementList.map(function (dropdownToggleEl) {
                new Dropdown(dropdownToggleEl)
            })
        }
    },
    computed: {
        ...mapGetters(['isUserToken']),
        ...mapState({
            profile: state => state.user.profile,
        }),
        firstName() {
            return _.head(_.split(this.profile.name, ' '))
        },
        firstCharacter() {
            return this.profile.name ? (this.profile.name).charAt(0) : undefined
        }
    }
}
</script>