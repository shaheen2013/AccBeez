<template>
    <div class="common-layout">
        <el-container class="full-width-container">
            <el-aside width="200px">
                <el-scrollbar>
                    <el-menu>
                        <router-link to="/bills" style="text-decoration: none;"
                                     :style="[$route.path === '/bills' ? {color: 'royalblue'} : {color: 'black'}]">
                            <el-menu-item index="1">
                                <!-- <el-icon><icon-menu /></el-icon> -->
                                <el-icon :size="20" style="width: 1em; height: 1em; margin-right: 8px">
                                    <Suitcase />
                                </el-icon>
                                Bills
                            </el-menu-item>
                        </router-link>
                        <router-link to="/registers" style="text-decoration: none;"
                                     :style="[$route.path === '/registers' ? {color: 'royalblue'} : {color: 'black'}]">
                            <el-menu-item index="2">
                                <!-- <el-icon><icon-menu /></el-icon> -->
                                <el-icon :size="20" style="width: 1em; height: 1em; margin-right: 8px">
                                    <List />
                                </el-icon>
                                Register
                            </el-menu-item>
                        </router-link>
                        <router-link to="/boms" style="text-decoration: none;"
                                     :style="[$route.path === '/boms' ? {color: 'royalblue'} : {color: 'black'}]">
                            <el-menu-item index="3">
                                <!-- <el-icon><icon-menu /></el-icon> -->
                                <el-icon :size="20" style="width: 1em; height: 1em; margin-right: 8px">
                                    <Money />
                                </el-icon>
                                BOM
                            </el-menu-item>
                        </router-link>
                        <router-link to="/users" style="text-decoration: none;"
                                     :style="[$route.path === '/users' ? {color: 'royalblue'} : {color: 'black'}]">
                            <el-menu-item index="4">
                                <!-- <el-icon><icon-menu /></el-icon> -->
                                <el-icon :size="20" style="width: 1em; height: 1em; margin-right: 8px">
                                    <User />
                                </el-icon>
                                Users
                            </el-menu-item>
                        </router-link>

                        <router-link to="/assign-users" style="text-decoration: none;"
                                     :style="[$route.path === '/assign-users' ? {color: 'royalblue'} : {color: 'black'}]">
                            <el-menu-item index="4">
                                <!-- <el-icon><icon-menu /></el-icon> -->
                                <el-icon :size="20" style="width: 1em; height: 1em; margin-right: 8px">
                                    <Avatar />
                                </el-icon>
                                Assign User
                            </el-menu-item>
                        </router-link>

                        <router-link to="/sales" style="text-decoration: none;"
                                     :style="[$route.path === '/sales' ? {color: 'royalblue'} : {color: 'black'}]">
                            <el-menu-item index="5">
                                <!-- <el-icon><icon-menu /></el-icon> -->
                                <el-icon :size="20" style="width: 1em; height: 1em; margin-right: 8px">
                                    <CreditCard />
                                </el-icon>
                                Sales
                            </el-menu-item>
                        </router-link>
                        <router-link to="/bomSales" style="text-decoration: none;"
                                     :style="[$route.path === '/bomSales' ? {color: 'royalblue'} : {color: 'black'}]">
                            <el-menu-item index="6">
                                <!-- <el-icon><icon-menu /></el-icon> -->
                                <el-icon :size="20" style="width: 1em; height: 1em; margin-right: 8px">
                                    <CreditCard />
                                </el-icon>
                                Bom Sales
                            </el-menu-item>
                        </router-link>
                        <router-link to="/cogs" style="text-decoration: none;"
                                     :style="[$route.path === '/cogs' ? {color: 'royalblue'} : {color: 'black'}]">
                            <el-menu-item index="7">
                                <!-- <el-icon><icon-menu /></el-icon> -->
                                <el-icon :size="20" style="width: 1em; height: 1em; margin-right: 8px">
                                    <CreditCard />
                                </el-icon>
                                Cogs
                            </el-menu-item>
                        </router-link>
                        <!-- <router-link to="/exl-table" style="text-decoration: none;"
                                     :style="[$route.path === '/exl-table' ? {color: 'royalblue'} : {color: 'black'}]">
                            <el-menu-item index="5">


                                Xl Table
                            </el-menu-item>
                        </router-link> -->
                    </el-menu>



                </el-scrollbar>
            </el-aside>

            <el-main>
                <router-view></router-view>
            </el-main>

        </el-container>
    </div>
</template>

<script>
</script>

<style scoped>
.warning-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
}

.content-wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.content-wrapper img {
    max-width: 100%;
    height: auto; /* Maintain the aspect ratio of the image */
    margin-bottom: 16px; /* Add margin below the image */
}
</style>
