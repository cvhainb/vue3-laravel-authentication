const Storefront = () => import(`./components/storefront/Storefront`)
const Index = () => import(`./components/storefront/Index`)
const UserLogin = () => import(`./components/storefront/UserLogin`)
const UserRegister = () => import(`./components/storefront/UserRegister`)
const UserLogout = () => import(`./components/storefront/UserLogout`)
const UserLayout = () => import(`./components/storefront/UserLayout`)
const UserDashboard = () => import(`./components/storefront/UserDashboard`)
const UserProfile = () => import(`./components/storefront/UserProfile`)
const UserGoogleMerchant = () => import(`./components/storefront/UserGoogleMerchant`)
const UserGoogleMerchantItems = () => import(`./components/storefront/UserGoogleMerchantItems`)
const UserGoogleMerchantSetting = () => import(`./components/storefront/UserGoogleMerchantSetting`)
const UserDataFeed = () => import(`./components/storefront/UserDataFeed`)
const UserOAuth2 = () => import(`./components/storefront/UserOAuth2`)
const UserOAuth2Google = () => import(`./components/storefront/UserOAuth2Google`)


const storefrontChildrenRoutes = [
    {
        path: '/',
        name: 'index',
        component: Index,
    },
    {
        path: '/login',
        name: 'login',
        component: UserLogin,
    },
    {
        path: '/register',
        name: 'register',
        component: UserRegister,
    },
    {
        path: '/store',
        name: 'user_panel',
        component: UserLayout,
        redirect: { name: `user_dashboard` },
        children: [
            {
                path: 'dashboard',
                name: 'user_dashboard',
                component: UserDashboard,
            },
            {
                path: 'profile',
                name: 'user_profile',
                component: UserProfile,
            },
            {
                path: 'google-merchant',
                name: 'user_google_merchant',
                component: UserGoogleMerchant,
                redirect: '/store/google-merchant/items',
                children: [
                    {
                        path: 'items',
                        name: 'user_google_merchant_items',
                        component: UserGoogleMerchantItems,
                    },
                    {
                        path: 'setting',
                        name: 'user_google_merchant_setting',
                        component: UserGoogleMerchantSetting,
                    },
                    
                ]
            },
            {
                path: 'datafeed',
                name: 'user_data_feed',
                component: UserDataFeed,
            },
            {
                path: 'oauth2',
                name: 'user_oauth2',
                component: UserOAuth2,
                children: [
                    {
                        path: 'google',
                        name: 'user_oauth2_google',
                        component: UserOAuth2Google,
                    }
                ]
            }
        ]
    },
    {
        path: '/logout',
        name: 'logout',
        component: UserLogout,
    },
]

/**
 * Admin Routes
 */
const Admin = () => import(`./components/admin/Admin`);
const AdminLogin = () => import(`./components/admin/Login`);
const AdminResetPassword = () => import(`./components/admin/ResetPassword.vue`);
const AdminDashboard = () => import(`./components/admin/Dashboard`);
 
const adminChildrenRoutes = [
    {
        path: 'dashboard',
        name: 'admin_dashboard',
        component: AdminDashboard,
    }
]

export const routes = [
    {
        path: '/',
        component: Storefront,
        children: storefrontChildrenRoutes
    },
    {
        path: '/admin',
        name: 'admin',
        redirect: '/admin/dashboard',
        component: Admin,
        children: adminChildrenRoutes
    },
    {
        path: '/admin/login',
        name: 'admin_login',
        component: AdminLogin,
    },
    {
        path: '/admin/reset-password',
        name: 'admin_reset_password',
        component: AdminResetPassword,
    },
]