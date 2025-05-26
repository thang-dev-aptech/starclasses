import React from "react";
import "bootstrap/dist/css/bootstrap.min.css";
import { Container, Nav, Navbar, Button } from "react-bootstrap";
import { Star } from "lucide-react";
import "../assets/css/component.css";

function MenuBar() {
  return (
    <Navbar bg="white" expand="lg" className="border-bottom p-0" sticky="top">
      <Container>
        {/* Logo */}
        <Navbar.Brand
          href="#"
          className="d-flex align-items-center fw-bold text-dark"
        >
          <Star className="text-warning me-2" size={24} />
          Star Classes
        </Navbar.Brand>

        {/* Navigation links */}
        <Nav className="ms-auto align-items-center fw-semibold">
          <Nav.Link href="#" className="text-dark px-3 hover-link">
            Home
          </Nav.Link>
          <Nav.Link href="#course" className="text-dark px-3 hover-link">
            Courses
          </Nav.Link>
          <Nav.Link href="#teacher" className="text-dark px-3 hover-link">
            Teachers
          </Nav.Link>
          <Nav.Link href="#" className="text-dark px-3 hover-link">
            Achievements
          </Nav.Link>
          <Nav.Link href="#contact" className="text-dark px-3 hover-link">
            Contact us
          </Nav.Link>
          <Nav.Link href="#" className="text-dark px-3 hover-link">
            Register
          </Nav.Link>

          {/* Đăng kí button */}
          <Button
            variant="warning"
            className="ms-3 fw-bold px-4"
            style={{
              animation: "pulse 1.5s infinite",
              transformOrigin: "center",
            }}
            href="#contact"
          >
            Get Started
          </Button>
        </Nav>
      </Container>
    </Navbar>
  );
}

export default MenuBar;
