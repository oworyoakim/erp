<template>
    <div class="mt-2">
        <!-- Page Tab -->
					<div class="page-menu">
						<div class="row">
							<div class="col-sm-12">
                            <p class="text-muted"> <h3>{{objective.name}}</h3></p>
                            <h5>Description</h5>
                            <p class="text-muted"> {{objective.description}}</p>
								<ul class="nav nav-tabs nav-tabs-bottom">
									<li class="nav-item">
										<a class="nav-link active" data-toggle="tab" href="#tab_interventions">Interventions</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-toggle="tab" href="#tab_outputs">Outputs</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-toggle="tab" href="#tab_indicators">Indicators</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-toggle="tab" href="#tab_milestones">Milestones</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<!-- /Page Tab -->
					
					<!-- Tab Content -->
					<div class="tab-content">
					
						<!-- interventions Tab -->
						<div class="tab-pane show active" id="tab_interventions">
						
							<!-- Add Addition Button -->
							<div class="text-right mb-4 clearfix">
								<button class="btn btn-primary add-btn" type="button" data-toggle="modal" data-target="#add_addition"><i class="fa fa-plus"></i> Add Interventions</button>
							</div>
							<!-- /Add Addition Button -->

							<!-- Payroll Additions Table -->
							<div class="payroll-table card">
								<div class="table-responsive">
									<table class="table table-hover table-radius">
										<thead>
											<tr>
												<th>Name</th>
												<th>Purpose</th>
												<th>Assigned To</th>
												<th class="text-right">Action</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<th>Talk to URA</th>
												<td>Asses all Tax returns</td>
												<td>Owor Yoakim</td>
												<td class="text-right">
													<div class="dropdown dropdown-action">
														<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
														<div class="dropdown-menu dropdown-menu-right">
															<a class="dropdown-item" href="#" data-toggle="modal" data-target="#edit_addition"><i class="fa fa-pencil m-r-5"></i> Edit</a>
															<a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_addition"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
														</div>
													</div>
												</td>
											</tr>
											<tr>
												<th>Talk to KCCA</th>
												<td>TO get permissions</td>
												<td>Amos Welike</td>
												<td class="text-right">
													<div class="dropdown dropdown-action">
														<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
														<div class="dropdown-menu dropdown-menu-right">
															<a class="dropdown-item" href="#" data-toggle="modal" data-target="#edit_addition"><i class="fa fa-pencil m-r-5"></i> Edit</a>
															<a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_addition"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
														</div>
													</div>
												</td>
											</tr>
											<tr>
												<th>Engage Bank of Uganda</th>
												<td>Buy some treasury Bills and Bonds</td>
												<td>Jackie Ochola</td>
												<td class="text-right">
													<div class="dropdown dropdown-action">
														<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
														<div class="dropdown-menu dropdown-menu-right">
															<a class="dropdown-item" href="#" data-toggle="modal" data-target="#edit_addition"><i class="fa fa-pencil m-r-5"></i> Edit</a>
															<a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_addition"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
														</div>
													</div>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<!-- /Payroll Additions Table -->
							
						</div>
						<div class="tab-pane show" id="tab_outputs">
						
							<!-- Add Addition Button -->
							<div class="text-right mb-4 clearfix">
								<button class="btn btn-primary add-btn" type="button" data-toggle="modal" data-target="#add_addition"><i class="fa fa-plus"></i> Add Output</button>
							</div>
							<!-- /Add Addition Button -->

							<!-- Payroll Additions Table -->
							<div class="payroll-table card">
								<div class="table-responsive">
									<table class="table table-hover table-radius">
										<thead>
											<tr>
												<th>Name</th>
												<th>Purpose</th>
												<th>Assigned To</th>
												<th class="text-right">Action</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<th>Talk to URA</th>
												<td>Asses all Tax returns</td>
												<td>Owor Yoakim</td>
												<td class="text-right">
													<div class="dropdown dropdown-action">
														<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
														<div class="dropdown-menu dropdown-menu-right">
															<a class="dropdown-item" href="#" data-toggle="modal" data-target="#edit_addition"><i class="fa fa-pencil m-r-5"></i> Edit</a>
															<a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_addition"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
														</div>
													</div>
												</td>
											</tr>
											<tr>
												<th>Talk to KCCA</th>
												<td>TO get permissions</td>
												<td>Amos Welike</td>
												<td class="text-right">
													<div class="dropdown dropdown-action">
														<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
														<div class="dropdown-menu dropdown-menu-right">
															<a class="dropdown-item" href="#" data-toggle="modal" data-target="#edit_addition"><i class="fa fa-pencil m-r-5"></i> Edit</a>
															<a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_addition"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
														</div>
													</div>
												</td>
											</tr>
											<tr>
												<th>Engage Bank of Uganda</th>
												<td>Buy some treasury Bills and Bonds</td>
												<td>Jackie Ochola</td>
												<td class="text-right">
													<div class="dropdown dropdown-action">
														<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
														<div class="dropdown-menu dropdown-menu-right">
															<a class="dropdown-item" href="#" data-toggle="modal" data-target="#edit_addition"><i class="fa fa-pencil m-r-5"></i> Edit</a>
															<a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_addition"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
														</div>
													</div>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<!-- /Payroll Additions Table -->
							
						</div>
						<!-- Additions Tab -->
						
						<!-- Overtime Tab -->
						<div class="tab-pane" id="tab_indicators">
						
							<!-- Add Overtime Button -->
							<div class="text-right mb-4 clearfix">
								<button class="btn btn-primary add-btn" type="button" data-toggle="modal" data-target="#add_overtime"><i class="fa fa-plus"></i> Add Indicator</button>
							</div>
							<!-- /Add Overtime Button -->

							<!-- Payroll Overtime Table -->
							<div class="payroll-table card">
								<div class="table-responsive">
									<table class="table table-hover table-radius">
										<thead>
											<tr>
												<th>Name</th>
												<th>Rate</th>
												<th>Output</th>
												<th class="text-right">Action</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<th>Acceptance by URA</th>
												<td>Hours invested</td>
												<td>Talk to URA</td>
												<td class="text-right">
													<div class="dropdown dropdown-action">
														<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
														<div class="dropdown-menu dropdown-menu-right">
															<a class="dropdown-item" href="#" data-toggle="modal" data-target="#edit_overtime"><i class="fa fa-pencil m-r-5"></i> Edit</a>
															<a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_overtime"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
														</div>
													</div>
												</td>
											</tr>
											<tr>
												<th>Approval by URA</th>
												<td>Documents signed</td>
												<td>Talk to URA</td>
												<td class="text-right">
													<div class="dropdown dropdown-action">
														<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
														<div class="dropdown-menu dropdown-menu-right">
															<a class="dropdown-item" href="#" data-toggle="modal" data-target="#edit_overtime"><i class="fa fa-pencil m-r-5"></i> Edit</a>
															<a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_overtime"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
														</div>
													</div>
												</td>
											</tr>
											<tr>
												<th>KCCA certificate</th>
												<td>Certificate of approval</td>
												<td>Talk to KCCA</td>
												<td class="text-right">
													<div class="dropdown dropdown-action">
														<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
														<div class="dropdown-menu dropdown-menu-right">
															<a class="dropdown-item" href="#" data-toggle="modal" data-target="#edit_overtime"><i class="fa fa-pencil m-r-5"></i> Edit</a>
															<a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_overtime"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
														</div>
													</div>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<!-- /Payroll Overtime Table -->
							
						</div>
						<!-- /Overtime Tab -->
						
						<!-- Deductions Tab -->
						<div class="tab-pane" id="tab_milestones">
						
							<!-- Add Deductions Button -->
							<div class="text-right mb-4 clearfix">
								<button class="btn btn-primary add-btn" type="button" data-toggle="modal" data-target="#add_deduction"><i class="fa fa-plus"></i> Add Milestone</button>
							</div>
							<!-- /Add Deductions Button -->

							<!-- Payroll Deduction Table -->
							<div class="payroll-table card">
								<div class="table-responsive">
									<table class="table table-hover table-radius">
										<thead>
											<tr>
												<th>Name</th>
												<th>Output</th>
												<th>Assigned To</th>
												<th class="text-right">Action</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<th>Carry out all KCCA work</th>
												<td>Talk to URA</td>
												<td>Owor Yoakim</td>
												<td class="text-right">
													<div class="dropdown dropdown-action">
														<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
														<div class="dropdown-menu dropdown-menu-right">
															<a class="dropdown-item" href="#" data-toggle="modal" data-target="#edit_deduction"><i class="fa fa-pencil m-r-5"></i> Edit</a>
															<a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_deduction"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
														</div>
													</div>
												</td>
											</tr>
											<tr>
												<th>Talk to Commissioner of URA</th>
												<td>Talk to URA</td>
												<td>Amos Welike</td>
												<td class="text-right">
													<div class="dropdown dropdown-action">
														<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
														<div class="dropdown-menu dropdown-menu-right">
															<a class="dropdown-item" href="#" data-toggle="modal" data-target="#edit_deduction"><i class="fa fa-pencil m-r-5"></i> Edit</a>
															<a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_deduction"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
														</div>
													</div>
												</td>
											</tr>
											<tr>
												<th>File all tax returns</th>
												<td>Talk to URA</td>
												<td>JAckie Ochola</td>
												<td class="text-right">
													<div class="dropdown dropdown-action">
														<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
														<div class="dropdown-menu dropdown-menu-right">
															<a class="dropdown-item" href="#" data-toggle="modal" data-target="#edit_deduction"><i class="fa fa-pencil m-r-5"></i> Edit</a>
															<a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_deduction"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
														</div>
													</div>
												</td>
											</tr>
											<tr>
												<th>Talk to KCCA PRO</th>
												<td>Talk to KCCA</td>
												<td>JAckie Ochola</td>
												<td class="text-right">
													<div class="dropdown dropdown-action">
														<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
														<div class="dropdown-menu dropdown-menu-right">
															<a class="dropdown-item" href="#" data-toggle="modal" data-target="#edit_deduction"><i class="fa fa-pencil m-r-5"></i> Edit</a>
															<a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_deduction"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
														</div>
													</div>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<!-- /Payroll Deduction Table -->
							
						</div>
						<!-- /Deductions Tab -->
						
					</div>
					<!-- Tab Content -->
                    <!-- Add Addition Modal -->
				<div id="add_addition" class="modal custom-modal fade" role="dialog">
					<div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Add Output</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<form>
									<div class="form-group">
										<label>Name <span class="text-danger">*</span></label>
										<input class="form-control" type="text">
									</div>
									<div class="form-group">
										<label>Purpose <span class="text-danger">*</span></label>
										<input class="form-control" type="text">
									</div>
									<div class="form-group">
										<label>Expected Date <span class="text-danger">*</span></label>
										<input class="form-control" type="text">
									</div>
									<!-- <div class="form-group">
										<label>Category <span class="text-danger">*</span></label>
										<select class="select">
											<option>Select a category</option>
											<option>Monthly remuneration</option>
											<option>Additional remuneration</option>
										</select>
									</div> -->
									<!-- <div class="form-group">
										<label class="d-block">Unit calculation</label>
										<div class="status-toggle">
											<input type="checkbox" id="unit_calculation" class="check">
											<label for="unit_calculation" class="checktoggle">checkbox</label>
										</div>
									</div> -->
									<!-- <div class="form-group">
										<label>Unit Amount</label>
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text">$</span>
											</div>
											<input type="text" class="form-control">
											<div class="input-group-append">
												<span class="input-group-text">.00</span>
											</div>
										</div>
									</div> -->
									<!-- <div class="form-group">
										<label class="d-block">Assignee</label>
										<div class="form-check form-check-inline">
											<input class="form-check-input" type="radio" name="addition_assignee" id="addition_no_emp" value="option1" checked>
											<label class="form-check-label" for="addition_no_emp">
											No assignee
											</label>
										</div>
										<div class="form-check form-check-inline">
											<input class="form-check-input" type="radio" name="addition_assignee" id="addition_all_emp" value="option2">
											<label class="form-check-label" for="addition_all_emp">
											All employees
											</label>
										</div>
										<div class="form-check form-check-inline">
											<input class="form-check-input" type="radio" name="addition_assignee" id="addition_single_emp" value="option3">
											<label class="form-check-label" for="addition_single_emp">
											Select Employee
											</label>
										</div>
										<div class="form-group">
											<select class="select">
												<option>-</option>
												<option>Select All</option>
												<option>John Doe</option>
												<option>Richard Miles</option>
											</select>
										</div>
									</div> -->
									<div class="submit-section">
										<button class="btn btn-primary submit-btn">Submit</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<!-- /Add Addition Modal -->
				
				<!-- Edit Addition Modal -->
				<div id="edit_addition" class="modal custom-modal fade" role="dialog">
					<div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Edit Output</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<form>
									<div class="form-group">
										<label>Name <span class="text-danger">*</span></label>
										<input class="form-control" type="text">
									</div>
									<div class="form-group">
										<label>Purpose <span class="text-danger">*</span></label>
										<input class="form-control" type="text">
									</div>
									<div class="form-group">
										<label>Assigned To <span class="text-danger">*</span></label>
										<input class="form-control" type="text">
									</div>
									<!-- <div class="form-group">
										<label>Category <span class="text-danger">*</span></label>
										<select class="select">
											<option>Select a category</option>
											<option>Monthly remuneration</option>
											<option>Additional remuneration</option>
										</select>
									</div> -->
									<!-- <div class="form-group">
										<label class="d-block">Unit calculation</label>
										<div class="status-toggle">
											<input type="checkbox" id="edit_unit_calculation" class="check">
											<label for="edit_unit_calculation" class="checktoggle">checkbox</label>
										</div>
									</div> -->
									<!-- <div class="form-group">
										<label>Unit Amount</label>
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text">$</span>
											</div>
											<input type="text" class="form-control">
											<div class="input-group-append">
												<span class="input-group-text">.00</span>
											</div>
										</div>
									</div> -->
									<!-- <div class="form-group">
										<label class="d-block">Assignee</label>
										<div class="form-check form-check-inline">
											<input class="form-check-input" type="radio" name="edit_addition_assignee" id="edit_addition_no_emp" value="option1" checked>
											<label class="form-check-label" for="edit_addition_no_emp">
											No assignee
											</label>
										</div>
										<div class="form-check form-check-inline">
											<input class="form-check-input" type="radio" name="edit_addition_assignee" id="edit_addition_all_emp" value="option2">
											<label class="form-check-label" for="edit_addition_all_emp">
											All employees
											</label>
										</div>
										<div class="form-check form-check-inline">
											<input class="form-check-input" type="radio" name="edit_addition_assignee" id="edit_addition_single_emp" value="option3">
											<label class="form-check-label" for="edit_addition_single_emp">
											Select Employee
											</label>
										</div>
										<div class="form-group">
											<select class="select">
												<option>-</option>
												<option>Select All</option>
												<option>John Doe</option>
												<option>Richard Miles</option>
											</select>
										</div>
									</div> -->
									<div class="submit-section">
										<button class="btn btn-primary submit-btn">Save</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<!-- /Edit Addition Modal -->
				
				<!-- Delete Addition Modal -->
				<div class="modal custom-modal fade" id="delete_addition" role="dialog">
					<div class="modal-dialog modal-dialog-centered">
						<div class="modal-content">
							<div class="modal-body">
								<div class="form-header">
									<h3>Delete Addition</h3>
									<p>Are you sure want to delete?</p>
								</div>
								<div class="modal-btn delete-action">
									<div class="row">
										<div class="col-6">
											<a href="javascript:void(0);" class="btn btn-primary continue-btn">Delete</a>
										</div>
										<div class="col-6">
											<a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /Delete Addition Modal -->
				
				<!-- Add Overtime Modal -->
				<div id="add_overtime" class="modal custom-modal fade" role="dialog">
					<div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Add Indicator</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<form>
									<div class="form-group">
										<label>Name <span class="text-danger">*</span></label>
										<input class="form-control" type="text">
									</div>
									<div class="form-group">
										<label>Rate Type <span class="text-danger">*</span></label>
										<select class="select">
											<option>-</option>
											<option>Daily Rate</option>
											<option>Hourly Rate</option>
										</select>
									</div>
									<div class="form-group">
										<label>Output <span class="text-danger">*</span></label>
										<input class="form-control" type="text">
									</div>
									<div class="submit-section">
										<button class="btn btn-primary submit-btn">Submit</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<!-- /Add Overtime Modal -->
				
				<!-- Edit Overtime Modal -->
				<div id="edit_overtime" class="modal custom-modal fade" role="dialog">
					<div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Edit Indicator</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<form>
									<div class="form-group">
										<label>Name <span class="text-danger">*</span></label>
										<input class="form-control" type="text">
									</div>
									<div class="form-group">
										<label>Rate Type <span class="text-danger">*</span></label>
										<select class="select">
											<option>-</option>
											<option>Daily Rate</option>
											<option>Hourly Rate</option>
										</select>
									</div>
									<div class="form-group">
										<label>Output <span class="text-danger">*</span></label>
										<input class="form-control" type="text">
									</div>
									<div class="submit-section">
										<button class="btn btn-primary submit-btn">Save</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<!-- /Edit Overtime Modal -->
				
				<!-- Delete Overtime Modal -->
				<div class="modal custom-modal fade" id="delete_overtime" role="dialog">
					<div class="modal-dialog modal-dialog-centered">
						<div class="modal-content">
							<div class="modal-body">
								<div class="form-header">
									<h3>Delete Overtime</h3>
									<p>Are you sure want to delete?</p>
								</div>
								<div class="modal-btn delete-action">
									<div class="row">
										<div class="col-6">
											<a href="javascript:void(0);" class="btn btn-primary continue-btn">Delete</a>
										</div>
										<div class="col-6">
											<a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /Delete Overtime Modal -->
				
				<!-- Add Deduction Modal -->
				<div id="add_deduction" class="modal custom-modal fade" role="dialog">
					<div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Add Milestone</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<form>
									<div class="form-group">
										<label>Name <span class="text-danger">*</span></label>
										<input class="form-control" type="text">
									</div>
									<div class="form-group">
										<label>Objective <span class="text-danger">*</span></label>
										<input class="form-control" type="text">
									</div>
									<div class="form-group">
										<label>Assigned To <span class="text-danger">*</span></label>
										<input class="form-control" type="text">
									</div>
									<!-- <div class="form-group">
										<label class="d-block">Unit calculation</label>
										<div class="status-toggle">
											<input type="checkbox" id="unit_calculation_deduction" class="check">
											<label for="unit_calculation_deduction" class="checktoggle">checkbox</label>
										</div>
									</div> -->
									<!-- <div class="form-group">
										<label>Unit Amount</label>
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text">$</span>
											</div>
											<input type="text" class="form-control">
											<div class="input-group-append">
												<span class="input-group-text">.00</span>
											</div>
										</div>
									</div> -->
									<!-- <div class="form-group">
										<label class="d-block">Assignee</label>
										<div class="form-check form-check-inline">
											<input class="form-check-input" type="radio" name="deduction_assignee" id="deduction_no_emp" value="option1" checked>
											<label class="form-check-label" for="deduction_no_emp">
											No assignee
											</label>
										</div>
										<div class="form-check form-check-inline">
											<input class="form-check-input" type="radio" name="deduction_assignee" id="deduction_all_emp" value="option2">
											<label class="form-check-label" for="deduction_all_emp">
											All employees
											</label>
										</div>
										<div class="form-check form-check-inline">
											<input class="form-check-input" type="radio" name="deduction_assignee" id="deduction_single_emp" value="option3">
											<label class="form-check-label" for="deduction_single_emp">
											Select Employee
											</label>
										</div>
										<div class="form-group">
											<select class="select">
												<option>-</option>
												<option>Select All</option>
												<option>John Doe</option>
												<option>Richard Miles</option>
											</select>
										</div>
									</div> -->
									<div class="submit-section">
										<button class="btn btn-primary submit-btn">Submit</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<!-- /Add Deduction Modal -->
				
				<!-- Edit Deduction Modal -->
				<div id="edit_deduction" class="modal custom-modal fade" role="dialog">
					<div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Edit Milestone</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<form>
									<div class="form-group">
										<label>Name <span class="text-danger">*</span></label>
										<input class="form-control" type="text">
									</div>
									<div class="form-group">
										<label>Objective <span class="text-danger">*</span></label>
										<input class="form-control" type="text">
									</div>
									<div class="form-group">
										<label>Assigned To <span class="text-danger">*</span></label>
										<input class="form-control" type="text">
									</div>
									<!-- <div class="form-group">
										<label class="d-block">Unit calculation</label>
										<div class="status-toggle">
											<input type="checkbox" id="edit_unit_calculation_deduction" class="check">
											<label for="edit_unit_calculation_deduction" class="checktoggle">checkbox</label>
										</div>
									</div> -->
									<!-- <div class="form-group">
										<label>Unit Amount</label>
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text">$</span>
											</div>
											<input type="text" class="form-control">
											<div class="input-group-append">
												<span class="input-group-text">.00</span>
											</div>
										</div>
									</div> -->
									<!-- <div class="form-group">
										<label class="d-block">Assignee</label>
										<div class="form-check form-check-inline">
											<input class="form-check-input" type="radio" name="edit_deduction_assignee" id="edit_deduction_no_emp" value="option1" checked>
											<label class="form-check-label" for="edit_deduction_no_emp">
											No assignee
											</label>
										</div>
										<div class="form-check form-check-inline">
											<input class="form-check-input" type="radio" name="edit_deduction_assignee" id="edit_deduction_all_emp" value="option2">
											<label class="form-check-label" for="edit_deduction_all_emp">
											All employees
											</label>
										</div>
										<div class="form-check form-check-inline">
											<input class="form-check-input" type="radio" name="edit_deduction_assignee" id="edit_deduction_single_emp" value="option3">
											<label class="form-check-label" for="edit_deduction_single_emp">
											Select Employee
											</label>
										</div>
										<div class="form-group">
											<select class="select">
												<option>-</option>
												<option>Select All</option>
												<option>John Doe</option>
												<option>Richard Miles</option>
											</select>
										</div>
									</div> -->
									<div class="submit-section">
										<button class="btn btn-primary submit-btn">Save</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<!-- /Edit Addition Modal -->
				
				<!-- Delete Deduction Modal -->
				<div class="modal custom-modal fade" id="delete_deduction" role="dialog">
					<div class="modal-dialog modal-dialog-centered">
						<div class="modal-content">
							<div class="modal-body">
								<div class="form-header">
									<h3>Delete Deduction</h3>
									<p>Are you sure want to delete?</p>
								</div>
								<div class="modal-btn delete-action">
									<div class="row">
										<div class="col-6">
											<a href="javascript:void(0);" class="btn btn-primary continue-btn">Delete</a>
										</div>
										<div class="col-6">
											<a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /Delete Deduction Modal -->
    </div>
    
</template>
<script>
export default {
    props:{
        objective: {
                type: Object,
                required: true
            }
    }
    
}
</script>
<style scoped>

</style>>