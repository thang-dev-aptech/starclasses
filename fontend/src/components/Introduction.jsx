import { Button } from "react-bootstrap";
import { ButtonCustom } from "./ButtonCustom";

export default function Introduction() {
  return (
    <div className="container py-5">
      <div className="row align-items-center">
        {/* Left Side */}
        <div className="col-lg-6 text-center text-lg-start">
          <span className="badge bg-warning text-dark fw-medium mb-3 px-3 py-2">
            Excellence in Education
          </span>
          <h1 className="fw-bold display-4 mb-3">
            Unlock Your Academic Potential with{" "}
            <span className="text-warning">Star Classes</span>
          </h1>
          <p className="text-muted mb-4">
            Personalized tutoring services designed to help students excel in
            their studies. Our expert tutors provide guidance, support, and the
            tools needed for academic success.
          </p>
          <div className="d-flex gap-3 justify-content-center justify-content-lg-start">
            {/* <button
              className="btn btn-warning text-dark fw-semibold px-4 py-2"
             
            >
              Get Started
            </button> */}
            <ButtonCustom
              className="btn btn-warning text-dark fw-semibold px-4 py-2"
              text="Get Started"
              href="#contact"
            />
            <button className="btn btn-outline-secondary fw-semibold px-4 py-2">
              Learn More
            </button>
          </div>
        </div>

        {/* Right Side (Image Placeholder) */}
        <div className="col-lg-6 mt-5 mt-lg-0 d-flex justify-content-center">
          <img
            src="../../image/introduction2.webp"
            alt="Introduction Image"
            className="img-fluid rounded shadow-lg my-5"
          />
        </div>
      </div>
    </div>
  );
}
