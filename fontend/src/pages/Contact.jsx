import "../assets/css/contact.css";
import { useState } from "react";
import { ButtonCustom } from "../components/ButtonCustom";
export const Contact = () => {
  const [successMessage, setSuccessMessage] = useState("");
  const [formData, setFormData] = useState({
    first_name: "",
    last_name: "",
    email: "",
    subject: "",
    phone: "",
    message: "",
  });
  const [errorMessage, setErrorMessage] = useState({
    first_name: "",
    last_name: "",
    email: "",
    subject: "",
    phone: "",
    message: "",
  });
  const handleOnchange = (e) => {
    const { name, value } = e.target;
    setFormData((prev) => ({
      ...prev,
      [name]: value,
    }));
    setErrorMessage((prev) => ({
      ...prev,
      [e.target.name]: "",
    }));
  };

  const handleSubmit = (e) => {
    e.preventDefault();
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

    if (!formData.subject) {
      setErrorMessage((prev) => ({
        ...prev,
        subject: "Please select a subject.",
      }));
      return;
    }
    if (!formData.phone || !formData.phone.match(/^\d{10}$/)) {
      setErrorMessage((prev) => ({
        ...prev,
        phone: "Please enter a valid phone number.",
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
    setSuccessMessage(
      "🎉 Cảm ơn bạn! Chúng tôi đã nhận được tin nhắn của bạn."
    );
    setTimeout(() => setSuccessMessage(""), 2000);

    setFormData({
      first_name: "",
      last_name: "",
      email: "",
      subject: "",
      phone: "",
      message: "",
    });
  };

  return (
    <>
      <div className="container bg-white py-5 ">
        <div className="text-center mb-5">
          <h1 className="fw-bold display-5">Contact Us</h1>
          <p className="text-secondary fs-5">
            Have questions? We're here to help. Reach out to us using the form
            below.
          </p>
        </div>

        <div className="row gx-5 gy-4">
          <div className="col-lg-6 col-md-12">
            <div className="border rounded p-4 shadow-sm contact-form h-100">
              <h5 className="fw-semibold mb-2">Send Us a Message</h5>
              <p className="text-secondary mb-4">
                Fill out the form and we'll get back to you as soon as possible.
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
                      onChange={handleOnchange}
                      value={formData.first_name}
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
                      onChange={handleOnchange}
                      value={formData.last_name}
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
                    onChange={handleOnchange}
                    value={formData.email}
                  />
                  <div className="text-danger">
                    {errorMessage.email && <span>{errorMessage.email}</span>}
                  </div>
                </div>
                <div className="mb-3">
                  <label className="form-label fw-medium">Subject</label>
                  <select
                    className="form-select"
                    name="subject"
                    onChange={handleOnchange}
                    value={formData.subject}
                  >
                    <option value="" disabled hidden>
                      --Choose Subject--
                    </option>
                    <option value="Mathematics">Mathematics</option>
                    <option value="Science">Science</option>
                    <option value="Humanities">Humanities</option>
                    <option value="Technology">Technology</option>
                  </select>
                  <div className="text-danger ">
                    {errorMessage.subject && (
                      <span>{errorMessage.subject}</span>
                    )}
                  </div>
                </div>

                <div className="mb-3 ">
                  <label className="form-label fw-medium">Phone Number</label>
                  <input
                    type="tel"
                    className="form-control"
                    placeholder="(123) 456-7890"
                    name="phone"
                    onChange={handleOnchange}
                    value={formData.phone}
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
                    onChange={handleOnchange}
                    value={formData.message}
                  ></textarea>
                  <div className="text-danger">
                    {errorMessage.message && (
                      <span>{errorMessage.message}</span>
                    )}
                  </div>
                  {successMessage && (
                    <div className="alert alert-success fw-semibold mt-3">
                      {successMessage}
                    </div>
                  )}
                </div>

                <ButtonCustom
                  text="Send Message"
                  className="btn btn-warning w-100 fw-semibold"
                  type="submit"
                />
              </form>
            </div>
          </div>

          <div className="col-lg-6 col-md-12">
            <div className="border rounded p-4 mb-4 shadow-sm contact-info">
              <h5 className="mb-2">Visit Our Center</h5>
              <p className="text-secondary">
                We're located in the heart of the city.
              </p>

              <div className="mb-3">
                <h6>Address</h6>
                <p className="text-secondary">
                  285 Đội Cấn, Ba Đình, Hà Nội, Việt Nam.
                </p>
              </div>

              <div className="mb-3">
                <h6>Hours</h6>
                <p className="text-secondary mb-0">
                  Monday - Friday: 9:00 AM - 8:00 PM
                </p>
                <p className="text-secondary mb-0">
                  Saturday: 10:00 AM - 4:00 PM
                </p>
                <p className="text-secondary">Sunday: Closed</p>
              </div>

              <div className="mb-2">
                <h6>Contact</h6>
                <p className="text-secondary mb-0">Phone: 1234567890</p>
                <p className="text-secondary">Email: info@starclasses.com</p>
              </div>
            </div>

            <div>
              <iframe
                title="Our Location"
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3723.9232491378457!2d105.81641017508112!3d21.03575678061532!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ab0d127a01e7%3A0xab069cd4eaa76ff2!2zMjg1IFAuIMSQ4buZaSBD4bqlbiwgTGnhu4V1IEdpYWksIEJhIMSQw6xuaCwgSMOgIE7hu5lpIDEwMDAwMCwgVmnhu4d0IE5hbQ!5e0!3m2!1svi!2s!4v1747225727624!5m2!1svi!2s"
                height="300"
                loading="lazy"
                referrerPolicy="no-referrer-when-downgrade"
                className="w-100 rounded border"
              ></iframe>
            </div>
          </div>
        </div>
      </div>
    </>
  );
};
