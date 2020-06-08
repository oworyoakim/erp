const routes = {
    // ACL
    USERS: '/acl/users',
    ROLES: '/acl/roles',
    GENERAL_SETTINGS: '/acl/settings/general',
    GENERAL_SETTINGS_JSON: '/acl/settings/general/all-json',
    ACL_FROM_SELECTIONS_OPTIONS: '/acl/form-selections-options',
    // hrms
    DASHBOARD: '/hrms/dashboard',
    DASHBOARD_STATISTICS_HRMS: '/hrms/dashboard-statistics',
    DIRECTORATES: '/hrms/directorates',
    DEPARTMENTS: '/hrms/departments',
    DIVISIONS: '/hrms/divisions',
    SECTIONS: '/hrms/sections',
    DESIGNATIONS: '/hrms/designations',
    SALARY_SCALES: '/hrms/salary-scales',
    LEAVES: '/hrms/leaves',
    LEAVES_JSON: '/hrms/leaves/all-json',
    LEAVE_TYPES: '/hrms/leaves/types',
    LEAVE_POLICIES: '/hrms/leaves/policies',
    LEAVE_APPLICATIONS: '/hrms/leave-applications',
    LEAVE_APPLICATION_STATUSES: '/hrms/leave-applications/statuses',
    LEAVE_APPLICATION_STATUSES_JSON: '/hrms/leave-applications/statuses/all-json',
    EMPLOYEES: '/hrms/employees',
    EMPLOYEES_TITLES: '/employees/titles',
    EMPLOYEES_GENDERS: '/hrms/employees/genders',
    EMPLOYEES_RELIGIONS: '/hrms/employees/religions',
    EMPLOYEES_MARITAL_STATUSES: '/hrms/employees/marital-statuses',
    EMPLOYEE_STATUSES: '/hrms/employees/employee-statuses',
    EMPLOYMENT_STATUSES: '/hrms/employees/employment-statuses',
    EMPLOYMENT_TERMS: '/hrms/employees/employment-terms',
    EMPLOYMENT_TYPES: '/hrms/employees/employment-types',
    EMPLOYEES_RELATIONSHIPS: '/hrms/employees/relationships',
    EMPLOYEES_EXPERIENCE: '/hrms/employees/experience',
    EMPLOYEES_ATTRIBUTES: '/hrms/form-selections-options',
    EMPLOYEE_DOCUMENTS: '/hrms/documents',
    APPROVAL_SETTINGS: '/hrms/settings/approvals',
    DELEGATIONS: '/hrms/delegations',

    // SPMS
    DASHBOARD_STATISTICS_SPMS: '/spms/dashboard/statistics',
    PLANS: '/spms/plans',
    SWOTS: '/spms/swots',
    SWOT_CATEGORIES: '/spms/swot-categories',

    OBJECTIVES: '/spms/objectives',
    INTERVENTIONS: '/spms/interventions',
    OUTPUTS: '/spms/outputs',
    OUTPUT_INDICATORS: '/spms/output-indicators',
    OUTPUT_TARGETS: '/spms/output-indicator-targets',
    OUTPUT_ACHIEVEMENTS: '/spms/output-achievements',

    KEY_RESULT_AREAS: '/spms/key-result-areas',
    OUTCOMES: '/spms/outcomes',
    OUTCOME_INDICATORS: '/spms/outcome-indicators',
    OUTCOME_TARGETS: '/spms/outcome-indicator-targets',
    OUTCOME_ACHIEVEMENTS: '/spms/outcome-achievements',
    WORK_PLANS: '/spms/work-plans',
    ACTIVITIES: '/spms/activities',
    STAGES: '/spms/stages',
    TASKS: '/spms/tasks',
};

export default routes;
