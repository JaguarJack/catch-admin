const en = {
  system: {
    name: 'CatchAdmin Dashboard',
    chinese: 'Chinese',
    english: 'English',
    confirm: 'Confirm',
    cancel: 'Cancel',
    warning: 'Warning',
    next: 'Next',
    prev: 'Prev',
    yes: 'Y',
    no: 'N',
    add: 'Add',
    finish: 'Finish',
    back: 'Back',
    update: 'Update',
  },

  login: {
    email: 'Email',
    password: 'Password',
    sign_in: 'Sign In',
    welcome: 'Welcome Back👏',
    lost_password: 'lost password?',
    remember: 'Remember me',
    verify: {
      email: {
        required: 'Please input email first',
        invalid: 'Email address is invalid',
      },

      password: {
        required: 'Please input password first',
      },
    },
  },

  register: {
    sign_up: 'Sign Up',
  },

  generate: {
    schema: {
      title: 'Create Schema',
      name: 'Schema Name',
      name_verify: 'please input schema name',
      engine: {
        name: 'Search Engine',
        verify: 'please select schema engine',
        placeholder: 'select schema engine',
      },
      default_field: {
        name: 'Default Field',
        created_at: 'Create time',
        updated_at: 'Update Time',
        creator: 'Creator',
        delete_at: 'SoftDelete',
      },
      comment: {
        name: 'Schema Comment',
        verify: 'please input schema comment',
      },

      structure: {
        title: 'Create Schema Structure',
        field_name: {
          name: 'Field Name',
          verify: 'please input field name',
        },
        length: 'Length',
        type: {
          name: 'Field Type',
          placeholder: 'select field type',
          verify: 'please select field type',
        },
        form_label: 'Form Label',
        form_component: 'Component',
        list: 'List',
        form: 'Form',
        unique: 'Unique',
        search: 'Search',
        search_op: {
          name: 'Search Operate',
          placeholder: 'select search operate',
        },
        nullable: 'Nullable',
        default: 'Default',
        rules: {
          name: 'Verify Rules',
          placeholder: 'select verify rules',
        },
        operate: 'Operate',
        comment: 'Field Comment',
      },
    },
    code: {
      title: 'Code Gen',
      module: {
        name: 'module',
        placeholder: 'please select module',
        verify: 'please select module first',
      },
      controller: {
        name: 'Controller',
        placeholder: 'please input controller name',
        verify: 'please input Controller name  first',
      },
      model: {
        name: 'Model',
        placeholder: 'please input model name',
        verify: 'please input model name  first',
      },
      paginate: 'Paginate',
    },
  },

  module: {
    create: 'Create Module',
    update: 'Update Module',
    form: {
      name: {
        title: 'Module Name',
        required: 'module name required',
      },

      path: {
        title: 'Module Path',
        required: 'module Path required',
      },

      desc: {
        title: 'Description',
      },

      keywords: {
        title: 'Keywords',
      },

      dirs: {
        title: 'Default Dirs',
        Controller: 'Controller',
        Model: 'Model',
        Database: 'Database',
        Request: 'Request',
      },
    },
  },
}

export default en
