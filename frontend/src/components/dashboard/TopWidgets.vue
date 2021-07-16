<template>
  <div class="kt-portlet">
    <div class="kt-portlet__body kt-portlet__body--fit">
      <div class="row row-no-padding row-col-separator-lg">
        <div class="col-md-12 col-lg-6 col-xl-3">
          <!--begin::Monthly new users-->
          <div class="kt-widget24">
            <div class="kt-widget24__details">
              <div class="kt-widget24__info">
                <h4 class="kt-widget24__title">No. of Users Added</h4>
                <span class="kt-widget24__desc"> Monthy New Users </span>
              </div>
              <span class="kt-widget24__stats kt-font-brand"> 125 </span>
            </div>
          </div>
          <!--end::Monthly new users-->
        </div>
        <div class="col-md-12 col-lg-6 col-xl-3">
          <!--begin::Monthly active users-->
          <div class="kt-widget24">
            <div class="kt-widget24__details">
              <div class="kt-widget24__info">
                <h4 class="kt-widget24__title">No. of Active Users</h4>
                <span class="kt-widget24__desc">
                  More than once logged in
                </span>
              </div>
              <span class="kt-widget24__stats kt-font-warning"> 321 </span>
            </div>
          </div>
          <!--end::New Feedbacks-->
        </div>
        <div class="col-md-12 col-lg-6 col-xl-3">
          <!--begin::New dealers added-->
          <div class="kt-widget24">
            <div class="kt-widget24__details">
              <div class="kt-widget24__info">
                <h4 class="kt-widget24__title">No. of New Dealers</h4>
                <span class="kt-widget24__desc">
                  Added dealers this month
                </span>
              </div>
              <span class="kt-widget24__stats kt-font-danger"> 5 </span>
            </div>
          </div>
          <!--end::New dealers added-->
        </div>

        <div class="col-md-12 col-lg-6 col-xl-3">
          <!--begin::New internet shops-->
          <div class="kt-widget24">
            <div class="kt-widget24__details">
              <div class="kt-widget24__info">
                <h4 class="kt-widget24__title">No. of Internet Shops</h4>
                <span class="kt-widget24__desc"> Shops posted this month </span>
              </div>
              <span class="kt-widget24__stats kt-font-success"> 1001 </span>
            </div>
          </div>
          <!--end::New internet shops-->
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: 'TopWidgets',
};
</script>
<style lang="stylus">
.topWidget
  height 100%

.kt-widget26__number
  color #777777
</style>
