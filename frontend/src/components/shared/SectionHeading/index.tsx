type SectionHeadingProps = {
  title: string;
  highlight?: string;
  description: string;
};

export default function SectionHeading({ title, highlight, description }: SectionHeadingProps) {
  return (
    <div className="mx-auto mb-10 max-w-3xl text-center">
      <h2 className="text-3xl font-extrabold tracking-tight text-slate-950 sm:text-4xl">
        {title}{" "}
        {highlight ? <span className="text-red-600">{highlight}</span> : null}
      </h2>
      <p className="mt-4 text-base text-slate-500">{description}</p>
    </div>
  );
}
