import { useState } from "react";
import { Modal } from "react-bootstrap";
import { ButtonCustom } from "./ButtonCustom";

export function ConsultModal({ show, handleClose }) {
  const [formData, setFormData] = useState({
    first_name: "",
    last_name: "",
    email: "",
    phone: "",
    message: "",
  });

  const [errorMessage, setErrorMessage] = useState({
    first_name: "",
    last_name: "",
    email: "",
    phone: "",
    message: "",
  });

  const [successMessage, setSuccessMessage] = useState("");

  const handleOnchange = (e) => {
    const { name, value } = e.target;
    setFormData((prev) => ({
      ...prev,
      [name]: value,
    }));
    setErrorMessage((prev) => ({
      ...prev,
      [name]: "",
    }));
  };

  const handleSubmit = (e) => {
    e.preventDefault();

    // Validate
    if (!formData.first_name) {
      setErrorMessage((prev) => ({
        ...prev,
        first_name: "Please enter your first name.",
      }));
      return;
    }
    if (!formData.last_name) {
      setErrorMessage((prev) => ({
        ...prev,
        last_name: "Please enter your last name.",
      }));
      return;
    }
    if (!/\S+@\S+\.\S+/.test(formData.email)) {
      setErrorMessage((prev) => ({
        ...prev,
        email: "Please enter a valid email address.",
      }));
      return;
    }
    if (!formData.phone || !formData.phone.match(/^\d{10}$/)) {
      setErrorMessage((prev) => ({
        ...prev,
        phone: "Please enter a valid 10-digit phone number.",
      }));
      return;
    }
    if (!formData.message) {
      setErrorMessage((prev) => ({
        ...prev,
        message: "Please enter a message.",
      }));
      return;
    }

    // Success
    setSuccessMessage(
      "🎉 Thank you! We have received your information and will contact you soon."
    );

    // Reset form after 2s
    setTimeout(() => {
      setSuccessMessage("");
      setFormData({
        first_name: "",
        last_name: "",
        email: "",
        phone: "",
        message: "",
      });
      handleClose();
    }, 2000);
  };

  return (
    <Modal show={show} onHide={handleClose} centered w-100>
      <Modal.Header closeButton>
        <Modal.Title className="text-center w-100 ">
          Sign up for consultation
        </Modal.Title>
      </Modal.Header>
      <Modal.Body>
        <p className="text-center text-warning">
          Please leave your information, we will contact you as soon as
          possible!
        </p>
        <form onSubmit={handleSubmit}>
          <div className="row mb-3">
            <div className="col-md-6 mb-3 mb-md-0">
              <label className="form-label fw-medium">First Name</label>
              <input
                type="text"
                className="form-control"
                placeholder="John"
                name="first_name"
                value={formData.first_name}
                onChange={handleOnchange}
              />
              <div className="text-danger">
                {errorMessage.first_name && (
                  <span>{errorMessage.first_name}</span>
                )}
              </div>
            </div>
            <div className="col-md-6">
              <label className="form-label fw-medium">Last Name</label>
              <input
                type="text"
                className="form-control"
                placeholder="Doe"
                name="last_name"
                value={formData.last_name}
                onChange={handleOnchange}
              />
              <div className="text-danger">
                {errorMessage.last_name && (
                  <span>{errorMessage.last_name}</span>
                )}
              </div>
            </div>
          </div>

          <div className="mb-3">
            <label className="form-label fw-medium">Email</label>
            <input
              type="email"
              className="form-control"
              placeholder="john.doe@example.com"
              name="email"
              value={formData.email}
              onChange={handleOnchange}
            />
            <div className="text-danger">
              {errorMessage.email && <span>{errorMessage.email}</span>}
            </div>
          </div>

          <div className="mb-3">
            <label className="form-label fw-medium">Phone Number</label>
            <input
              type="tel"
              className="form-control"
              placeholder="1234567890"
              name="phone"
              value={formData.phone}
              onChange={handleOnchange}
            />
            <div className="text-danger">
              {errorMessage.phone && <span>{errorMessage.phone}</span>}
            </div>
          </div>

          <div className="mb-4">
            <label className="form-label fw-medium">Message</label>
            <textarea
              className="form-control"
              placeholder="Type your message here."
              name="message"
              rows="4"
              value={formData.message}
              onChange={handleOnchange}
            ></textarea>
            <div className="text-danger">
              {errorMessage.message && <span>{errorMessage.message}</span>}
            </div>
          </div>

          {successMessage && (
            <div className="alert alert-success fw-semibold mb-3 text-center">
              {successMessage}
            </div>
          )}

          <ButtonCustom
            text="Send Message"
            className="btn btn-warning w-100 fw-semibold"
            type="submit"
          />
        </form>
      </Modal.Body>
    </Modal>
  );
}
