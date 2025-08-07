<!-- Attendance Modal -->
<div class="modal fade" id="attendanceModal" tabindex="-1" role="dialog" aria-labelledby="attendanceModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="attendanceModalLabel">
                    <i class="fas fa-map-marker-alt"></i> Mark Your Attendance
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-center mb-4">Choose how you'd like to verify your attendance at the game:</p>

                <!-- Verification Methods -->
                <div class="row">
                    <!-- Manual Check-in -->
                    <div class="col-md-4 mb-3">
                        <div class="card h-100 verification-method" onclick="markAttendance('manual')" style="cursor: pointer;">
                            <div class="card-body text-center">
                                <i class="fas fa-hand-point-up fa-3x text-primary mb-3"></i>
                                <h6 class="card-title">Manual Check-in</h6>
                                <p class="card-text small text-muted">
                                    Simple one-click check-in. Quick and easy!
                                </p>
                                <button type="button" class="btn btn-outline-primary btn-sm">
                                    Check In Now
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- GPS Verification -->
                    <div class="col-md-4 mb-3">
                        <div class="card h-100 verification-method" onclick="requestGPS()" style="cursor: pointer;">
                            <div class="card-body text-center">
                                <i class="fas fa-map-marker-alt fa-3x text-success mb-3"></i>
                                <h6 class="card-title">GPS Verification</h6>
                                <p class="card-text small text-muted">
                                    Verify you're at the venue using your location.
                                </p>
                                <button type="button" class="btn btn-outline-success btn-sm">
                                    Use Location
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- QR Code Scan -->
                    <div class="col-md-4 mb-3">
                        <div class="card h-100 verification-method" onclick="showQRScanner()" style="cursor: pointer;">
                            <div class="card-body text-center">
                                <i class="fas fa-qrcode fa-3x text-info mb-3"></i>
                                <h6 class="card-title">Scan QR Code</h6>
                                <p class="card-text small text-muted">
                                    Scan the QR code displayed at the venue.
                                </p>
                                <button type="button" class="btn btn-outline-info btn-sm">
                                    Scan Code
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- QR Scanner Section (Initially Hidden) -->
                <div id="qrScannerSection" style="display: none;">
                    <hr>
                    <h6 class="text-center">
                        <i class="fas fa-qrcode"></i> QR Code Scanner
                    </h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="qrCodeInput">Enter QR Code:</label>
                                <input type="text" id="qrCodeInput" class="form-control" placeholder="Enter or scan QR code">
                            </div>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <button type="button" class="btn btn-info" onclick="submitQRCode()">
                                <i class="fas fa-check"></i> Verify Code
                            </button>
                        </div>
                    </div>
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i>
                        Look for QR codes at the venue entrance or ask venue staff.
                    </small>
                </div>

                <!-- Loading State -->
                <div id="attendanceLoading" style="display: none;">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Processing...</span>
                        </div>
                        <p class="mt-2">Verifying your attendance...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <div class="ml-auto">
                    <small
