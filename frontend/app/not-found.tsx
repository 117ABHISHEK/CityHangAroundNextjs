import Link from "next/link";

export default function NotFound() {
  return (
    <main
      style={{
        display: "flex",
        flexDirection: "column",
        alignItems: "center",
        justifyContent: "center",
        minHeight: "60vh",
        padding: "40px 24px",
        textAlign: "center",
      }}
    >
      <h1
        style={{
          fontSize: "72px",
          fontWeight: 800,
          lineHeight: 1,
          marginBottom: "8px",
          color: "#e2e8f0",
        }}
      >
        404
      </h1>

      <p
        style={{
          fontSize: "18px",
          fontWeight: 500,
          color: "#94a3b8",
          marginBottom: "32px",
        }}
      >
        This page doesn&apos;t exist yet.
      </p>

      <Link
        href="/"
        style={{
          display: "inline-flex",
          alignItems: "center",
          gap: "8px",
          padding: "10px 24px",
          borderRadius: "8px",
          backgroundColor: "#3b82f6",
          color: "#fff",
          fontSize: "14px",
          fontWeight: 600,
          textDecoration: "none",
          transition: "background-color 0.2s",
        }}
      >
        ← Back to Home
      </Link>
    </main>
  );
}
