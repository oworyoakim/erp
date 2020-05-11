/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import Vue from "vue";

require("./app");

import Dashboard from "./components/hrms/dashboard/Dashboard";
import Leaves from "./components/hrms/leaves/Leaves";
import LeavesList from "./components/hrms/leaves/LeavesList";
import LeaveStatistics from "./components/hrms/leaves/LeaveStatistics";
import LeaveTypes from "./components/hrms/leave-types/LeaveTypes";
import LeaveTypesList from "./components/hrms/leave-types/LeaveTypesList";
import LeaveTypeForm from "./components/hrms/leave-types/LeaveTypeForm";
import LeaveApplications from "./components/hrms/leave-applications/LeaveApplications";
import LeaveApplicationsList from "./components/hrms/leave-applications/LeaveApplicationsList";
import LeaveApplicationForm from "./components/hrms/leave-applications/LeaveApplicationForm";
import Employees from "./components/hrms/employees/Employees";
import EmployeesList from "./components/hrms/employees/EmployeesList";
import EmployeeProfile from "./components/hrms/employees/EmployeeProfile";
import DownloandEmployeeProfile from "./components/hrms/employees/DownloandEmployeeProfile";
import EmployeeProfileForm from "./components/hrms/employees/EmployeeProfileForm";
import EmployeeProfileWidget from "./components/hrms/employees/EmployeeProfileWidget";
import EmployeeWidget from "./components/hrms/employees/EmployeeWidget";
import Directorates from "./components/hrms/directorates/Directorates";
import DirectorateDetails from "./components/hrms/directorates/DirectorateDetails";
import Departments from "./components/hrms/departments/Departments";
import DepartmentDetails from "./components/hrms/departments/DepartmentDetails";
import Designations from "./components/hrms/designations/Designations";
import DesignationsList from "./components/hrms/designations/DesignationsList";
import DesignationForm from "./components/hrms/designations/DesignationForm";
import Sections from "./components/hrms/sections/Sections";
import SalaryScales from "./components/hrms/salary-scales/SalaryScales";
import SalaryScalesList from "./components/hrms/salary-scales/SalaryScalesList";
import SalaryScaleForm from "./components/hrms/salary-scales/SalaryScaleForm";
import PersonalInfo from "./components/hrms/employees/PersonalInfo";
import RelatedPersonsInfo from "./components/hrms/related-persons/RelatedPersonsInfo";
import RelatedPersonsInfoForm from "./components/hrms/related-persons/RelatedPersonsInfoForm";
import RelatedPersonsInfoList from "./components/hrms/related-persons/RelatedPersonsInfoList";
import ExperienceInfo from "./components/hrms/experience/ExperienceInfo";
import ExperienceInfoList from "./components/hrms/experience/ExperienceInfoList";
import ExperienceInfoForm from "./components/hrms/experience/ExperienceInfoForm";
import EducationInfo from "./components/hrms/education/EducationInfo";
import EducationInfoList from "./components/hrms/education/EducationInfoList";
import EducationInfoForm from "./components/hrms/education/EducationInfoForm";
import BankInfo from "./components/hrms/bank/BankInfo";
import ContactsInfo from "./components/hrms/employees/ContactsInfo";
import CreateEmployee from "./components/hrms/employees/CreateEmployee";
import LeavesInfo from "./components/hrms/employees/LeavesInfo";
import Documents from "./components/hrms/documents/Documents";
import EmployeeDocuments from "./components/hrms/documents/EmployeeDocuments";
import EmployeeDocumentsList from "./components/hrms/documents/EmployeeDocumentsList";
import EmployeeDocumentForm from "./components/hrms/documents/EmployeeDocumentForm";
import Divisions from "./components/hrms/divisions/Divisions";
import DivisionDetails from "./components/hrms/divisions/DivisionDetails";
import ExecutiveSecretary from "./components/hrms/executive-secretary/ExecutiveSecretary";
import LeavePolicies from "./components/hrms/leave-types/LeavePolicies";
import LeavePolicyForm from "./components/hrms/leave-types/LeavePolicyForm";

import Delegations from "./components/hrms/delegations/Delegations";
import DelegationsList from "./components/hrms/delegations/DelegationsList";
import DelegationForm from "./components/hrms/delegations/DelegationForm";

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */
Vue.component("app-admin-dashboard", Dashboard);
Vue.component("app-leaves", Leaves);
Vue.component("app-leaves-list", LeavesList);
Vue.component("app-leave-types", LeaveTypes);
Vue.component("app-leave-types-list", LeaveTypesList);
Vue.component("app-leave-type-form", LeaveTypeForm);
Vue.component("app-leave-applications", LeaveApplications);
Vue.component("app-leave-applications-list", LeaveApplicationsList);
Vue.component("app-leave-application-form", LeaveApplicationForm);
Vue.component("app-leave-statistics", LeaveStatistics);
Vue.component("app-employees", Employees);
Vue.component("app-employees-list", EmployeesList);
Vue.component("app-employee-profile", EmployeeProfile);
Vue.component("app-download-employee-profile", DownloandEmployeeProfile);
Vue.component("app-employee-profile-form", EmployeeProfileForm);
Vue.component("app-employee-profile-widget", EmployeeProfileWidget);
Vue.component("app-employee-widget", EmployeeWidget);
Vue.component("app-create-employee", CreateEmployee);
Vue.component("app-directorates", Directorates);
Vue.component("app-directorate-details", DirectorateDetails);
Vue.component("app-departments", Departments);
Vue.component("app-department-details", DepartmentDetails);
Vue.component("app-designations", Designations);
Vue.component("app-designations-list", DesignationsList);
Vue.component("app-designation-form", DesignationForm);
Vue.component("app-sections", Sections);
Vue.component("app-salary-scales", SalaryScales);
Vue.component("app-salary-scales-list", SalaryScalesList);
Vue.component("app-salary-scale-form", SalaryScaleForm);
Vue.component("app-personal-info", PersonalInfo);
Vue.component("app-related-persons-info", RelatedPersonsInfo);
Vue.component("app-related-persons-info-list", RelatedPersonsInfoList);
Vue.component("app-related-persons-info-form", RelatedPersonsInfoForm);
Vue.component("app-experience-info", ExperienceInfo);
Vue.component("app-experience-info-list", ExperienceInfoList);
Vue.component("app-experience-info-form", ExperienceInfoForm);
Vue.component("app-education-info", EducationInfo);
Vue.component("app-education-info-list", EducationInfoList);
Vue.component("app-education-info-form", EducationInfoForm);
Vue.component("app-leaves-info", LeavesInfo);
Vue.component("app-bank-info", BankInfo);
Vue.component("app-contacts-info", ContactsInfo);
Vue.component("app-divisions", Divisions);
Vue.component("app-division-details", DivisionDetails);
Vue.component("app-executive-secretary", ExecutiveSecretary);
Vue.component("app-leave-policies", LeavePolicies);
Vue.component("app-leave-policy-form", LeavePolicyForm);
Vue.component("app-employee-documents", EmployeeDocuments);
Vue.component("app-employee-documents-list", EmployeeDocumentsList);
Vue.component("app-employee-document-form", EmployeeDocumentForm);
Vue.component("app-documents-manager", Documents);
Vue.component("app-delegations", Delegations);
Vue.component("app-delegations-list", DelegationsList);
Vue.component("app-delegation-form", DelegationForm);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
import hrmsStore from './stores/hrms';

const app = new Vue({
    el: "#main-app",
    store: hrmsStore
});

hrmsStore.dispatch("getUser").then(() => {
    if (!window.intervalId) {
        window.intervalId = setInterval(async () => {
            await hrmsStore.dispatch("getUser");
        }, 60000);
    }
}).catch(error => {
    console.error(error);
});
