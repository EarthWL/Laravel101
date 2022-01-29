<template id="delUser">
    <swal-title>
      Do you want to <b style="color: red">Delete</b> this User ?
    </swal-title>
    <swal-icon type="warning" color="red"></swal-icon>
    <swal-button type="confirm">
      Yes, Delete
    </swal-button>
    <swal-button type="cancel">
      Cancel
    </swal-button>
    <swal-param name="allowEscapeKey" value="false" />
    <swal-param
      name="customClass"
      value='{ "popup": "my-popup" }' />
</template>

<template id="restoreUser">
  <swal-title>
    Do you want to restore this User ?
  </swal-title>
  <swal-icon type="question" color="sky"></swal-icon>
  <swal-button type="confirm">
    Yes, Restore
  </swal-button>
  <swal-button type="cancel">
    Cancel
  </swal-button>
  <swal-param name="allowEscapeKey" value="false" />
  <swal-param
    name="customClass"
    value='{ "popup": "my-popup" }' />
</template>